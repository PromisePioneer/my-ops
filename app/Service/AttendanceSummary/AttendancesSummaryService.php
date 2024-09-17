<?php

namespace App\Service\AttendanceSummary;

use App\Models\LeaveAndPermission;
use App\Models\NationalHoliday;
use App\Models\User;
use App\Models\WorkTime;
use App\Service\FinancialClosePeriodService;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class AttendancesSummaryService
{

    private FinancialClosePeriodService $financialClosePeriodService;

    public function __construct()
    {
        $this->financialClosePeriodService = new FinancialClosePeriodService();
    }

    public function data(): LengthAwarePaginator
    {
        $startDate = $this->financialClosePeriodService->startDate();
        $endDate = $this->financialClosePeriodService->endDate();

        $data = User::with([
            'attendance' => function ($query) use ($startDate, $endDate) {
                $query->whereBetween('timestamp', [$startDate, $endDate]);
            },
            'userHasWorkTime.workTime',
            'roles' => function ($query) {
                $query->whereNotIn('name', ['Super Admin']);
            },
        ])->paginate(10);

        return self::formattedData($data, $startDate, $endDate);
    }

    public function formattedData(LengthAwarePaginator $attendanceSummary, $startDate, $endDate): LengthAwarePaginator
    {
        $data = $attendanceSummary->getCollection()->map(function ($attendance) use ($startDate, $endDate) {
            $attendanceGroupBy = self::attendancesGroupedBy($attendance);
            $totalPresent = $attendance->attendance->where('status1', 0)->count();
            $totalMinutesLate = self::calculateLate($attendanceGroupBy, $attendance);
            $totalLeaves = self::calculateLeaves($attendance->id, $startDate, $endDate);
            $totalSick = self::calculateSick($attendance->id, $startDate, $endDate);
            $totalAbsent = self::calculateAlpha(
                $startDate,
                $endDate,
                $attendance->attendance->where('status1', 0)->count()
            );
            $totalPermission = self::calculatePermission($attendance->id, $startDate, $endDate);


            return [
                'id' => $attendance->id,
                'user_nip' => $attendance->nip,
                'user_name' => $attendance->name ?? null,
                'work_time' => $attendance?->userHasWorkTime?->workTime,
                'total_present' => $totalPresent ?? 0,
                'total_late_in_minutes' => (int)$totalPresent ?? $totalMinutesLate ?? 0,
                'total_leaves' => $totalLeaves ?? 0,
                'total_sick' => $totalSick ?? 0,
                'total_absent' => $totalAbsent ?? 0,
                'total_permission' => $totalPermission ?? 0,

            ];
        });

        $attendanceSummary->setCollection($data);
        return $attendanceSummary;
    }

    public function attendancesGroupedBy($attendance)
    {
        return $attendance->attendance->groupBy(function ($query) {
            return $query->timestamp;
        });
    }

    private static function calculateLate($attendanceGroupBy, $attendance): int
    {
        $calculateLateGroupBy = $attendanceGroupBy->groupBy(function ($item) {
            return $item->first()->employee_id.'-'.Carbon::parse($item->first()->timestamp)->format('Y-m-d');
        });


        foreach ($calculateLateGroupBy as $day => $dailyItems) {
            $checkIn = $dailyItems->where('status1', 0)->first();
            $userWorktime = WorkTime::where('name', 'Default')->first();
            if ($checkIn) {
                $expectedCheckInTime = $userWorktime->clock_in;
                $expectedCheckIn = Carbon::parse($checkIn->first()->timestamp)->format(
                        'Y-m-d'
                    ).' '.$expectedCheckInTime;
                $actualCheckIn = Carbon::parse($checkIn->first()->timestamp);

                if ($actualCheckIn->greaterThan($expectedCheckIn)) {
                    return Carbon::parse($expectedCheckIn)->diffInMinutes($actualCheckIn);
                }
            }
        }
        return 0;
    }

    private static function calculateLeaves(int $userId, $startDate, $endDate): int
    {
        $startPeriod = self::leavesQuery($userId, $startDate, $endDate)
            ->where('leaves_status', 'Cuti')
            ->first();
        $endPeriod = self::leavesQuery($userId, $startDate, $endDate)
            ->where('leaves_status', 'Cuti')
            ->latest()
            ->first();

        return Carbon::parse($startPeriod?->start_date)->diffInDays($endPeriod?->end_date);
    }

    private static function leavesQuery(int $userId, $startDate, $endDate)
    {
        return LeaveAndPermission::where('user_id', $userId)
            ->whereBetween('start_date', [$startDate, $endDate])
            ->where('confirmation_status', 'Diterima');
    }

    private static function calculateSick(int $userId, $startDate, $endDate): int
    {
        $startPeriod = self::leavesQuery($userId, $startDate, $endDate)
            ->where('leaves_status', 'Sakit')
            ->first();
        $endPeriod = self::leavesQuery($userId, $startDate, $endDate)
            ->where('leaves_status', 'Sakit')
            ->latest()
            ->first();


        return Carbon::parse($startPeriod?->start_date)->diffInDays($endPeriod?->end_date);
    }

    public function calculateAlpha($startDate, $endDate, $totalPresent): int
    {
        $nationalHoliday = NationalHoliday::whereBetween('date', [$startDate, $endDate])->count();
        $getWeekEndHoliday = (int)$startDate->diffInWeek($endDate) - 1 ?? 0;

        return $startDate->diffInDays($endDate) - $nationalHoliday - $getWeekEndHoliday - $totalPresent;
    }

    private static function calculatePermission(int $userId, $startDate, $endDate): int
    {
        $startPeriod = self::leavesQuery($userId, $startDate, $endDate)
            ->where('leaves_status', 'Izin')
            ->first();
        $endPeriod = self::leavesQuery($userId, $startDate, $endDate)
            ->where('leaves_status', 'Izin')
            ->latest()
            ->first();


        return Carbon::parse($startPeriod?->start_date)->diffInDays($endPeriod?->end_date);
    }


    public function search(Request $request): LengthAwarePaginator
    {
        $search = $request->input('search');
        $startDate = Carbon::parse($request->start_date) ?? $this->financialClosePeriodService->endDate();
        $endDate = Carbon::parse($request->end_date) ?? $this->financialClosePeriodService->endDate();

        $query = User::with([
            'attendance' => function ($query) use ($startDate, $endDate) {
                $query->whereBetween('timestamp', [$startDate, $endDate]);
            },
            'userHasWorkTime',
            'roles' => function ($query) {
                $query->whereNotIn('name', ['Super Admin']);
            },
        ]);


        if (!empty($search)) {
            $query->where('name', 'like', "%".$search."%")
                ->orWhere('nip', 'like', "%".$search."%");
        }


        $data = $query->paginate(10);
        return self::formattedData($data, $startDate, $endDate);
    }


    public function filterByDate(Request $request): LengthAwarePaginator
    {
        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);


        $data = User::with([
            'attendance' => function ($query) use ($startDate, $endDate) {
                $query->whereBetween('timestamp', [$startDate, $endDate]);
            },
            'userHasWorkTime',
            'roles' => function ($query) {
                $query->whereNotIn('name', ['Super Admin']);
            },
        ])->paginate(10);

        return self::formattedData($data, $startDate, $endDate);
    }
}