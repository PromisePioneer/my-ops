<?php

namespace App\Support\Attendances\AttendanceSummary;

use AllowDynamicProperties;
use App\Models\EmployeeSchedule;
use App\Models\WeekHoliday;
use App\Support\Attendances\AttendanceQueryFilter;
use App\Support\Attendances\AttendancesACLFilter;
use App\Support\Attendances\EmployeeSchedule\Repository\EmployeeScheduleRepository;
use App\Support\Attendances\Repository\AttendancesSummaryRepository;
use App\Support\Attendances\WeekHoliday\Repository\WeekHolidayRepository;
use App\Support\HelperService\FinancialClosePeriodService;
use App\Support\User\LeaveAndPermission\Repository\LeaveAndPermissionRepository;
use Carbon\Carbon;
use Carbon\CarbonInterval;
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
        $this->attendancesSummaryRepository = new AttendancesSummaryRepository();
        $this->employeeScheduleRepository = new EmployeeScheduleRepository();
        $this->weekHolidayRepository = new WeekHolidayRepository();
        $this->leaveAndPermissionRepository = new LeaveAndPermissionRepository();
    }

    public function data(Request $request): LengthAwarePaginator
    {
        $query = $this->attendancesSummaryRepository->getAttendancesSummary($this->startDate, $this->endDate);
        $attendanceSummary = AttendancesACLFilter::apply($query, $request)->paginate(self::$perPage);
        $weeklyLateCount = $this->weeklyLateCount($attendanceSummary);
        $userIds = $attendanceSummary->getCollection()->pluck('id');
        $absentIds = $attendanceSummary->getCollection()->pluck('absent_id');
        $weekHolidays = $this->weekHolidayRepository->getBasedOnUserId($userIds);
        $employeeSchedules = $this->employeeScheduleRepository->getBasedOnPeriodsAndAbsentId($this->startDate, $this->endDate, $absentIds);
        $periods = CarbonPeriod::create($this->startDate, $this->endDate)->toArray();

        return self::formattedData($weeklyLateCount, $attendanceSummary, $this->startDate, $this->endDate, $weekHolidays, $employeeSchedules, $periods, $request);
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


        $query = $this->attendancesSummaryRepository->getAttendancesSummary($startDate, $endDate);

        if (!empty($search)) {
            $query->where('name', 'like', '%' . $search . '%');
        }
        $attendanceSummary = AttendancesACLFilter::apply($query, $request)->paginate(self::$perPage);
        $weeklyLateCount = $this->weeklyLateCount($attendanceSummary);
        $userIds = $attendanceSummary->getCollection()->pluck('id');
        $absentIds = $attendanceSummary->getCollection()->pluck('absent_id');
        $weekHolidays = $this->weekHolidayRepository->getBasedOnUserId($userIds);
        $employeeSchedules = $this->employeeScheduleRepository->getBasedOnPeriodsAndAbsentId($startDate, $endDate, $absentIds);
        $periods = CarbonPeriod::create($startDate, $endDate)->toArray();


        return self::formattedData($weeklyLateCount, $attendanceSummary, $startDate, $endDate, $weekHolidays, $employeeSchedules, $periods, $request);
    }


    public function filter($request): LengthAwarePaginator
    {
        $startDate = Carbon::make($request->start_date) ?? $this->startDate;
        $endDate = Carbon::make($request->end_date) ?? $this->endDate;


        $query = $this->attendancesSummaryRepository->getAttendancesSummary($startDate, $endDate);

        $filterQuery = AttendanceQueryFilter::apply($query, $request);
        $attendanceSummary = AttendancesACLFilter::apply($filterQuery, $request)->paginate(self::$perPage);
        $weeklyLateCount = $this->weeklyLateCount($attendanceSummary);
        $userIds = $attendanceSummary->getCollection()->pluck('id');
        $absentIds = $attendanceSummary->getCollection()->pluck('absent_id');
        $weekHolidays = $this->weekHolidayRepository->getBasedOnUserId($userIds);
        $employeeSchedules = $this->employeeScheduleRepository->getBasedOnPeriodsAndAbsentId($startDate, $endDate, $absentIds);
        $periods = CarbonPeriod::create($startDate, $endDate)->toArray();

        return self::formattedData($weeklyLateCount, $attendanceSummary, $startDate, $endDate, $weekHolidays, $employeeSchedules, $periods, $request);
    }


    public function formattedData($weeklyLateCount, LengthAwarePaginator $attendancesSummary, $startDate, $endDate, $weekHolidays, $employeeSchedules, $periods, $request = null): LengthAwarePaginator
    {
        $data = $attendancesSummary->getCollection()->map(function ($user) use (
            $weeklyLateCount,
            $attendancesSummary,
            $startDate,
            $endDate,
            $weekHolidays,
            $employeeSchedules,
            $periods,
            $request
        ) {


            $weekHoliday = $weekHolidays[$user->id] ?? null;
            $employeeHolidays = $employeeSchedules[$user->absent_id] ?? collect();


            $employeeHolidayDates = $employeeHolidays->pluck('start_date')->toArray();
            $leaveDates = $this->getLeaveDates($user->leaveAndPermissions, $startDate, $endDate);
            $notCheckIn = $user->attendancesSummary->where('clock_in', null)->count();
            $notCheckOut = $user->attendancesSummary->where('clock_out', null)->count();


            return [
                'id' => $user->id,
                'user_nip' => $user->nip,
                'user_name' => $user->name,
                'profile_pic' => $user->profile_pic,
                'role' => $user->roles[0]->name ?? '',
                'total_minutes_late' => $this->totalLateCount($user, $weeklyLateCount),
                'total_not_check_in' => $notCheckIn,
                'total_not_check_out' => $notCheckOut,
                'total_present' => $user->attendancesSummary->count(),
                'work_period_count' => $this->workPeriodTotal($periods, $weekHoliday, $employeeHolidayDates, $leaveDates),
                'total_leaves' => $this->calculateLeaveDays($user, $startDate, $endDate, 'Cuti'),
                'total_sick' => $this->calculateLeaveDays($user, $startDate, $endDate, 'Sakit'),
                'total_permission' => $this->calculateLeaveDays($user, $startDate, $endDate, 'Izin'),
                'total_important_leaves' => $this->calculateLeaveDays($user, $startDate, $endDate, 'Cuti Penting'),
                'total_overtime' => $this->calculateLeaveDays($user, $startDate, $endDate, 'Lembur'),
                'total_absent' => $this->getAbsentCount($user->attendancesSummary, $periods, $leaveDates, $employeeHolidayDates, $weekHoliday),
            ];
        });


        $sorted = $data;

        if ($request?->sorting === 'Alfa') {
            $sorted = $data->sortByDesc('total_absent')->values();
        }

        if ($request?->sorting === 'Sakit') {
            $sorted = $data->sortByDesc('total_sick')->values();
        }

        if ($request?->sorting === 'Izin') {
            $sorted = $data->sortByDesc('total_permission')->values();
        }


        $attendancesSummary->setCollection($sorted);
        return $attendancesSummary;
    }


    private function totalLateCount($user, $weeklyLateCount): string
    {
        $weeklyLate = $weeklyLateCount[$user->id] ?? collect([]);
        $lateTotal = 0;
        foreach ($weeklyLate as $late) {
            $lateTotal += $late;
        }


        return number_format($lateTotal);
    }


    public function getAbsentCount($attendanceSummary, $periods, $leaveDates, $employeeHolidayDates, $weekHoliday): int
    {
        $attendedDates = $attendanceSummary->pluck('date')->toArray();



        // dd($employeeHolidayDates);

        return collect($periods)
            ->reject(function ($period) {
                return $period->greaterThanOrEqualTo(Carbon::today());
            })->reject(function ($period) use ($leaveDates) {
                return in_array($period->format('Y-m-d'), $leaveDates);
            })->reject(function ($period) use ($attendedDates) {
                return in_array($period->format('Y-m-d'), $attendedDates);
            })->reject(function ($period) use ($employeeHolidayDates, $weekHoliday) {
                if (in_array($period->format('Y-m-d'), $employeeHolidayDates)) {
                    return true;
                }
                return $period?->dayName === $weekHoliday?->day;
            })->count();
    }


    public function workPeriodTotal($periods, $weekHoliday, $employeeHolidayDates, $leaveDates): int
    {
        return collect($periods)
            ->reject(function ($period) use ($weekHoliday, $employeeHolidayDates) {
                if (in_array($period->format('Y-m-d'), $employeeHolidayDates)) {
                    return true;
                } else {
                    return $period?->dayName === $weekHoliday?->day;
                }
            })->reject(function ($period) use ($leaveDates) {
                return in_array($period->format('Y-m-d'), $leaveDates);
            })->count();
    }


    public function getLeaveDates($leaveAndPermissions, $startDate, $endDate): array
    {

        $getLeaves = $leaveAndPermissions->whereBetween('start_date', [$startDate, $endDate]);

        $firstLeaveStartDate = $leaveAndPermissions->min('start_date');
        $lastLeaveEndDate = $leaveAndPermissions->max('end_date');

        $leavePeriods = [];
        foreach ($getLeaves as $ignored) {
            $leavePeriods = array_merge(
                $leavePeriods,
                CarbonPeriod::create($firstLeaveStartDate, $lastLeaveEndDate)->toArray()
            );
        }

        $leaveDates = [];
        foreach ($leavePeriods as $date) {
            $formattedDate = Carbon::parse($date)->format('Y-m-d');
            $leaveDates[] = $formattedDate;
        }

        return $leaveDates;

    }

    private function calculateLeaveDays($user, $startDate, $endDate, $type): float|int
    {
        $periodStart = Carbon::parse($startDate);
        $periodEnd = Carbon::parse($endDate);
        $totalDays = 0;

        foreach ($user->leaveAndPermissions->where('leaves_status', $type)->where('confirmation_status', 'Diterima') as $leave) {
            $leaveStart = Carbon::parse($leave->start_date);
            $leaveEnd = Carbon::parse($leave->end_date);

            $overlapStart = $leaveStart->max($periodStart);
            $overlapEnd = $leaveEnd->min($periodEnd);

            if ($overlapStart->gt($overlapEnd)) continue;

            $totalDays += $overlapStart->diffInDays($overlapEnd) + 1;
        }

        return $totalDays;
    }


    public function weeklyLateCount($users): array
    {

        $weeklyLatenessMap = [];
        foreach ($users as $user) {
            $attendances = $user->attendancesSummary;

            $attendancesGroupedByWeek = collect($attendances)->groupBy(function ($item) {
                $date = Carbon::parse($item->date);
                $diffInDays = $this->startDate->diffInDays($date);
                $groupNumber = (int)($diffInDays / 7);
                return $this->startDate->copy()->addDays($groupNumber * 7 + 6)->toDateString();
            });

            foreach ($attendancesGroupedByWeek as $weekEndDate => $weekAttendances) {
                $weekLatenessTotal = 0;

                foreach ($weekAttendances as $attendance) {
                    $workTime = $attendance->workTime;
                    if (!$workTime || !$attendance->clock_in) {
                        continue;
                    }

                    $expectedCheckIn = Carbon::parse("{$attendance->date} {$workTime->clock_in}");
                    $actualCheckIn = Carbon::parse($attendance->clock_in);


                    $newExpectedCheckIn = null;
                    if ($workTime->name === 'Malam') {
                        $newExpectedCheckIn = $expectedCheckIn->copy()->addDay();
                    }

                    $employeeSchedule = EmployeeSchedule::where('employee_id', $user->absent_id)
                        ->whereDate('start_date', $attendance->date)
                        ->first();

                    $weekHoliday = WeekHoliday::where('user_id', $user->id)
                        ->where('day', Carbon::parse($attendance->date)->dayName)
                        ->first();

                    if ($actualCheckIn->greaterThan($expectedCheckIn)) {
                        if (($employeeSchedule?->status !== 'L') || !$weekHoliday) {
                            $latenessInMinutes = $newExpectedCheckIn ?
                                $newExpectedCheckIn->diffInMinutes($actualCheckIn) :
                                $expectedCheckIn->diffInMinutes($actualCheckIn);
                            $weekLatenessTotal += (int)CarbonInterval::minutes($latenessInMinutes)->format('%i');
                        }
                    }
                }

                if ($weekLatenessTotal > 15) {
                    $weeklyLatenessMap[$user->id][$weekEndDate] = $weekLatenessTotal;
                }
            }
        }
        return $weeklyLatenessMap;
    }


    public function getTotalCheckInOrCheckOut($periods, $leaveDates, $employeeHolidayDates, $weekHoliday, $type): int
    {
        return collect($periods)
            ->reject(function ($period) use ($leaveDates) {
                return in_array($period->format('Y-m-d'), $leaveDates);
            })->reject(function ($period) use ($employeeHolidayDates, $weekHoliday) {
                if (in_array($period->format('Y-m-d'), $employeeHolidayDates)) {
                    return true;
                }
                return $period?->dayName === $weekHoliday?->day;
            })->filter(function ($period) use ($type) {
                return in_array($period->format('Y-m-d'), $type);
            })->count();
    }
}
