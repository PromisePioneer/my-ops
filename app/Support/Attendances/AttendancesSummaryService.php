<?php

namespace App\Support\Attendances;

use AllowDynamicProperties;
use App\Models\EmployeeSchedule;
use App\Models\User;
use App\Models\WeekHoliday;
use App\Support\HelperService\FinancialClosePeriodService;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
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
        $query = User::select('id', 'nip', 'name', 'profile_pic', 'active', 'absent_id')
            ->with([
                'attendancesSummary' => function ($query) {
                    $query->select('employee_id', 'date', 'clock_in', 'clock_out', 'work_time_id')
                        ->whereBetween('date', [$this->startDate, $this->endDate])
                        ->with('workTime:id,clock_in,name');
                },
                'employeeSchedules' => function ($query) {
                    $query->select('employee_id', 'start_date', 'status')
                        ->where('start_date', '<=', $this->startDate)
                        ->orderBy('start_date', 'asc');
                },
                'leaveAndPermissions' => function ($query) {
                    $query->select('user_id', 'start_date', 'end_date', 'confirmation_status', 'leaves_status')
                        ->where('confirmation_status', 'Diterima')
                        ->where(function ($q) {
                            $q->whereBetween('start_date', [$this->startDate, $this->endDate])
                                ->orWhereBetween('end_date', [$this->startDate, $this->endDate]);
                        });
                },
                'roles:id,name',
                'weekHoliday:user_id,day'
            ])
            ->where('active', 1)
            ->orderBy('absent_id');

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


        // Ambil semua user IDs dan absent_ids
        $userIds = $user->getCollection()->pluck('id');
        $absentIds = $user->getCollection()->pluck('absent_id');

        $weekHolidays = WeekHoliday::whereIn('user_id', $userIds)
            ->get()
            ->keyBy('user_id'); // Indexed by user_id untuk akses cepat
        $employeeSchedules = EmployeeSchedule::whereIn('employee_id', $absentIds)
            ->whereBetween('start_date', [$startDate, $endDate])
            ->where('status', 'L')
            ->get()
            ->groupBy('employee_id'); // Indexed by employee_id untuk akses cepat

        // Periode kerja
        $periods = CarbonPeriod::create($startDate, $endDate)->toArray();
        $totalWorkDays = count($periods);

        $data = $user->getCollection()->map(function ($user) use ($startDate, $endDate, $weekHolidays, $employeeSchedules, $periods, $totalWorkDays) {


            $totalPresent = $user->attendancesSummary->count();
            $totalSick = $this->calculateLeaveDays($user, $startDate, $endDate, 'Sakit');
            $totalLeaves = $this->calculateLeaveDays($user, $startDate, $endDate, 'Cuti');
            $totalPermission = $this->calculateLeaveDays($user, $startDate, $endDate, 'Izin');
            $totalMinutesLate = $this->calculateLate($user->attendancesSummary);
            $totalNotCheckIn = $user->attendancesSummary->whereNull('clock_in')->count();
            $totalNotCheckOut = $user->attendancesSummary
                ->where('date', '!=', Carbon::today()->format('Y-m-d'))
                ->whereNull('clock_out')->count();


            // Ambil data libur mingguan dan jadwal libur karyawan
            $weekHoliday = $weekHolidays[$user->id] ?? null;
            $employeeHolidays = $employeeSchedules[$user->absent_id] ?? collect();

            $employeeHolidayDates = $employeeHolidays->pluck('start_date')->toArray();


            $totalPeriodOfWork = collect($periods)->reject(function ($period) use ($weekHoliday, $employeeHolidayDates) {
                return $weekHoliday?->day === $period->dayName || in_array($period->format('Y-m-d'), $employeeHolidayDates);
            })->count();


            $totalPeriodOfWork -= ($totalLeaves + $totalSick + $totalPermission);

            $totalAbsent = $totalPeriodOfWork - $totalPresent + $totalLeaves + $totalSick + $totalPermission;

            return [
                'id' => $user->id,
                'user_nip' => $user->nip,
                'user_name' => $user->name,
                'profile_pic' => $user->profile_pic,
                'role' => $user->roles[0]->name ?? '',
                'total_minutes_late' => (int)$totalMinutesLate,
                'total_not_check_in' => $totalNotCheckIn,
                'total_not_check_out' => $totalNotCheckOut,
                'total_present' => $totalPresent + $totalLeaves + $totalSick + $totalPermission . '/' . $totalPeriodOfWork,
                'total_leaves' => $totalLeaves,
                'total_sick' => $totalSick,
                'total_permission' => $totalPermission,
                'total_absent' => $totalAbsent,
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


    public function calculateLate($attendanceSummary): float|int|null
    {
        $totalLate = 0;
        foreach ($attendanceSummary as $attendance) {
            $actualCheckIn = Carbon::make($attendance?->clock_in ?? $attendance->date);
            $workDate = $attendance?->date;
            $expectedCheckIn = Carbon::parse("$workDate {$attendance->workTime?->clock_in}");

            $newExpectedCheckIn = null;
            if ($attendance->workTime?->name === "Malam") {
                $newExpectedCheckIn = $expectedCheckIn->copy()->addDays();
            }

            $checkInToUse = $newExpectedCheckIn ?? $expectedCheckIn;


            // If the difference is greater than 3 minutes, count it as late
            $lateMinutes = $checkInToUse->diffInMinutes($actualCheckIn);

            if ($lateMinutes > 3) {
                $totalLate += $lateMinutes;
            }
        }
        return $totalLate;
    }


    public function filter($request): LengthAwarePaginator
    {
        $startDate = Carbon::make($request->start_date) ?? $this->startDate;
        $endDate = Carbon::make($request->end_date) ?? $this->endDate;


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
