<?php

namespace App\Support\Attendances;

use AllowDynamicProperties;
use App\Models\User;
use App\Support\HelperService\FinancialClosePeriodService;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

#[AllowDynamicProperties] class AttendancesSummaryService
{
    private static int $perPage = 10;

    public function __construct()
    {
        $this->financialClosePeriodService = new FinancialClosePeriodService();
        $this->startDate = $this->financialClosePeriodService->startDate();
        $this->endDate = $this->financialClosePeriodService->endDate();
    }

    public function data(Request $request): LengthAwarePaginator
    {
        $query = User::with([
            'attendancesSummary' => function ($query) {
                $query->whereBetween('date', [$this->startDate, $this->endDate])->with('workTime');
            },
            'employeeSchedules' => function ($query) {
                $query->where('start_date', '<=', $this->startDate)
                    ->orderBy('start_date', 'asc');
            },
            'leaveAndPermissions' => function ($query) {
                $query->where('confirmation_status', 'Diterima')
                    ->where(function ($q) {
                        $q->whereBetween('start_date', [$this->startDate, $this->endDate])
                            ->orWhereBetween('end_date', [$this->startDate, $this->endDate]);
                    });
            },
            'roles',
            'weekHoliday'
        ])->where('active', 1)->orderBy('absent_id');

        $data = AttendancesACLFilter::apply($query, $request);
        $attendanceSummary = $data->paginate(self::$perPage)->onEachSide(1);
        return self::formattedData($attendanceSummary, $this->startDate, $this->endDate);
    }


    private function getScheduledDays($user, $startDate, $endDate): float|int
    {
        $schedules = $user->employeeSchedules->sortBy('start_date');
        $periodStart = Carbon::parse($startDate);
        $periodEnd = Carbon::parse($endDate);
        $scheduledDays = 0;

        $prevDate = $periodStart->copy();
        foreach ($schedules as $schedule) {
            $scheduleStart = Carbon::parse($schedule->start_date);
            if ($scheduleStart > $periodEnd) break;

            $nextScheduleStart = $schedules->where('start_date', '>', $schedule->start_date)
                ->first()->start_date ?? $periodEnd->addDay();

            $scheduleEnd = Carbon::parse($nextScheduleStart)->subDay();
            $effectiveStart = $scheduleStart->max($periodStart);
            $effectiveEnd = $scheduleEnd->min($periodEnd);

            if ($effectiveStart > $effectiveEnd) continue;

            if ($schedule->status !== 'L') {
                $scheduledDays += $effectiveStart->diffInDays($effectiveEnd) + 1;
            }

            $prevDate = $effectiveEnd->addDay();
        }

        if ($prevDate <= $periodEnd) {
            $scheduledDays += $prevDate->diffInDays($periodEnd) + 1;
        }

        return $scheduledDays;
    }


    public function formattedData(LengthAwarePaginator $user, $startDate, $endDate): LengthAwarePaginator
    {

        $data = $user->getCollection()->map(function ($user) use ($startDate, $endDate) {
            $totalPresent = $user->attendancesSummary->count();
            $totalSick = $this->calculateLeaveDays($user, $startDate, $endDate, 'Sakit');
            $totalLeaves = $this->calculateLeaveDays($user, $startDate, $endDate, 'Cuti');
            $totalPermission = $this->calculateLeaveDays($user, $startDate, $endDate, 'Izin');
            $scheduledDays = $this->getScheduledDays($user, $startDate, $endDate);

            $totalAbsent = max(
                $scheduledDays - ($totalPresent + $totalLeaves + $totalSick + $totalPermission),
                0
            );

            $totalMinutesLate = $user->attendancesSummary->sum(function ($attendance) {
                return $this->calculateLate($attendance->workTime, $attendance);
            });

            $totalNotCheckIn = $user->attendancesSummary->whereNull('clock_in')->count();
            $totalNotCheckOut = $user->attendancesSummary
                ->where('date', '!=', Carbon::today()->format('Y-m-d'))
                ->whereNull('clock_out')->count();


            return [
                'id' => $user->id,
                'user_nip' => $user->nip,
                'user_name' => $user?->name,
                'profile_pic' => $user->profile_pic,
                'role' => $user->roles[0]?->name ?? '',
                'total_minutes_late' => (int)$totalMinutesLate,
                'total_not_check_in' => $totalNotCheckIn,
                'total_not_check_out' => $totalNotCheckOut,
                'total_present' => $totalPresent,
                'total_leaves' => $totalLeaves,
                'total_sick' => $totalSick,
                'total_permission' => $totalPermission,
                'total_absent' => $totalAbsent > 0 ? $totalAbsent - $totalLeaves : 0
            ];
        });


        $user->setCollection($data);
        return $user;
    }

    private function calculateLeaveDays($user, $startDate, $endDate, $type): float|int
    {
        $periodStart = Carbon::parse($startDate);
        $periodEnd = Carbon::parse($endDate);
        $totalDays = 0;

        foreach ($user->leaveAndPermissions->where('leaves_status', $type) as $leave) {
            $leaveStart = Carbon::parse($leave->start_date);
            $leaveEnd = Carbon::parse($leave->end_date);

            $overlapStart = $leaveStart->max($periodStart);
            $overlapEnd = $leaveEnd->min($periodEnd);

            if ($overlapStart->gt($overlapEnd)) continue;

            $totalDays += $overlapStart->diffInDays($overlapEnd) + 1;
        }

        return $totalDays;
    }

    public function getLeaves($user, $startDate, $endDate): int
    {
        $leaveStatus = 'Cuti';
        return $this->calculateLeaveDays($user, $startDate, $endDate, $leaveStatus);
    }

    public function getSick($user, $startDate, $endDate): int
    {

        $leaveStatus = 'Sakit';
        return $this->calculateLeaveDays($user, $startDate, $endDate, $leaveStatus);

    }


    public function getPermission($user, $startDate, $endDate): int
    {
        $leaveStatus = 'Izin';
        return $this->calculateLeaveDays($user, $startDate, $endDate, $leaveStatus);
    }



    public function calculateLate($userWorktime, $attendance): float|int
    {
        $actualCheckIn = Carbon::make($attendance?->clock_in ?? $attendance->date);
        $workDate = $attendance?->date;
        $expectedCheckIn = Carbon::parse("$workDate {$userWorktime?->clock_in}");

        $newExpectedCheckIn = null;
        if ($userWorktime?->name === "Malam") {
            $newExpectedCheckIn = $expectedCheckIn->copy()->addDays();
        }

        $checkInToUse = $newExpectedCheckIn ?? $expectedCheckIn;


        $diffInSeconds = $actualCheckIn->diffInSeconds($expectedCheckIn, true);

        // If actual check-in is earlier than or exactly on time, no late
        if ($diffInSeconds <= 0) {
            return 0;
        }

        // Convert to minutes and check against the 2.5-minute threshold
        $diffInMinutes = $diffInSeconds / 60;

        if ($diffInMinutes < 2.5) {
            return 0;
        }

        return $checkInToUse->diffInMinutes($actualCheckIn);
    }


    public function filter($request): LengthAwarePaginator
    {
        $startDate = $request->start_date ?? $this->startDate;
        $endDate = $request->end_date ?? $this->endDate;


        $query = User::with([
            'attendancesSummary' => function ($query) use ($startDate, $endDate, $request) {
                $query->whereBetween('date', [$startDate, $endDate]);
            }
        ], 'branch')->where('active', 1);


        $filter = AttendanceQueryFilter::apply($query, $request);
        $aclFilter = AttendancesACLFilter::apply($filter, $request);

        $attendanceSummary = $aclFilter->paginate(self::$perPage)->onEachSide(1);
        return self::formattedData($attendanceSummary, $startDate, $endDate);
    }


    public function search(Request $request): LengthAwarePaginator
    {
        $startDate = isset($request->start_date)
            ? Carbon::parse($request->start_date)
            : $this->financialClosePeriodService->startDate();
        $endDate = isset($request->end_date)
            ? Carbon::parse($request->end_date)
            : $this->financialClosePeriodService->endDate();
        $search = $request->input('search');


        $users = User::search($search)->query(function ($query) use ($startDate, $endDate, $request) {
            $query = $query->with([
                'attendancesSummary' => function ($query) use ($startDate, $endDate, $request) {
                    $query->whereBetween('date', [$startDate, $endDate]);
                }
            ], 'branch');
            AttendancesACLFilter::apply($query, $request);
        });


        $user = $users->paginate(self::$perPage)->onEachSide(1);
        return self::formattedData($user, $startDate, $endDate);
    }
}
