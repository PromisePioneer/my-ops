<?php

namespace App\Support\Attendances;

use AllowDynamicProperties;
use App\Models\EmployeeSchedule;
use App\Models\LeaveAndPermission;
use App\Models\User;
use App\Models\WeekHoliday;
use App\Models\WorkTime;
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
                    $query->select('date', 'employee_id', 'date', 'clock_in', 'clock_out', 'work_time_id')
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
                'weekHoliday:user_id,day',
                'company:id,name',
            ])
            ->where('active', 1)
            ->orderBy('absent_id');


        $users = $query->get();
        $weeklyLatenessMap = [];

        foreach ($users as $user) {
            $attendancesGroupedByWeek = collect($user->attendancesSummary)
                ->groupBy(function ($attendance) {
                    return Carbon::parse($attendance->date)->endOfWeek()->format('Y-m-d');
                });

            foreach ($attendancesGroupedByWeek as $weekEndDate => $attendances) {
                $weekLatenessTotal = 0;

                foreach ($attendances as $attendance) {
                    $workTime = $attendance->workTime;
                    if (!$workTime || !$attendance->clock_in) continue;

                    $expectedCheckIn = Carbon::parse("{$attendance->date} {$workTime->clock_in}");
                    $actualCheckIn = Carbon::parse($attendance->clock_in);

                    if ($workTime->name === "Malam") {
                        $expectedCheckIn->addDay();
                    }

                    if ($actualCheckIn->greaterThan($expectedCheckIn)) {
                        $lateness = $expectedCheckIn->diffInSeconds($actualCheckIn);
                        $weekLatenessTotal += $lateness;
                    }
                }

                if ($weekLatenessTotal > 900) {
                    $weeklyLatenessMap[$user->id][$weekEndDate] = $weekLatenessTotal / 60;
                }
            }
        }

        $data = AttendancesACLFilter::apply($query, $request);
        $attendanceSummary = $data->paginate(self::$perPage)->onEachSide(1);
        return self::formattedData($weeklyLatenessMap, $attendanceSummary, $this->startDate, $this->endDate);
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


    public function formattedData($weeklyLatenessMap, LengthAwarePaginator $user, $startDate, $endDate): LengthAwarePaginator
    {
        $userIds = $user->getCollection()->pluck('id');
        $absentIds = $user->getCollection()->pluck('absent_id');

        $weekHolidays = WeekHoliday::whereIn('user_id', $userIds)
            ->get()
            ->keyBy('user_id');
        $employeeSchedules = EmployeeSchedule::whereIn('employee_id', $absentIds)
            ->whereBetween('start_date', [$startDate, $endDate])
            ->where('status', 'L')
            ->get()
            ->groupBy('employee_id');

        $periods = CarbonPeriod::create($startDate, $endDate)->toArray();

        $data = $user->getCollection()->map(function ($user) use ($weeklyLatenessMap, $startDate, $endDate, $weekHolidays, $employeeSchedules, $periods) {


            $weeklyLate = $weeklyLatenessMap[$user->id] ?? collect([]);


            $totalMinutesLate = 0;
            foreach ($weeklyLate as $late) {
                $totalMinutesLate += $late;
            }


            $totalPresent = $user->attendancesSummary->count();
            $totalSick = $this->calculateLeaveDays($user, $startDate, $endDate, 'Sakit');
            $totalLeaves = $this->calculateLeaveDays($user, $startDate, $endDate, 'Cuti');
            $totalPermission = $this->calculateLeaveDays($user, $startDate, $endDate, 'Izin');
            $totalOvertime = $this->calculateOvertime($user, $startDate, $endDate, 'Lembur');
            $totalImportantLeaves = $this->calculateLeaveDays($user, $startDate, $endDate, 'Cuti Penting');
            $totalNotCheckIn = $user->attendancesSummary->whereNull('clock_in')->count();
            $totalNotCheckOut = $user->attendancesSummary
                ->where('date', '!=', Carbon::today()->format('Y-m-d'))
                ->whereNull('clock_out')->count();


            $weekHoliday = $weekHolidays[$user->id] ?? null;
            $employeeHolidays = $employeeSchedules[$user->absent_id] ?? collect();

            $employeeHolidayDates = $employeeHolidays->pluck('start_date')->toArray();


            $getLeaves = LeaveAndPermission::where('user_id', $user->id)
                ->whereBetween('start_date', [$startDate, $endDate])
                ->get();


            $firstStartDate = $getLeaves->min('start_date');
            $lastEndDate = $getLeaves->max('end_date');

            $leavePeriods = [];
            foreach ($getLeaves as $dates) {
                $leavePeriods = array_merge(
                    $leavePeriods,
                    CarbonPeriod::create($firstStartDate, $lastEndDate)->toArray()
                );
            }

            $leaveDates = [];
            foreach ($leavePeriods as $date) {
                $formattedDate = Carbon::parse($date)->format('Y-m-d');
                $leaveDates[] = $formattedDate;
            }


            $totalPeriodOfWork = collect($periods)
                ->reject(function ($period) use ($weekHoliday, $employeeHolidayDates) {
                    if (in_array($period->format('Y-m-d'), $employeeHolidayDates)) {
                        return true;
                    } else {
                        return $period?->dayName === $weekHoliday?->day;
                    }
                })->reject(function ($period) use ($leaveDates, $leavePeriods) {
                    return in_array($period->format('Y-m-d'), $leaveDates);
                })->count();


            $attendedDates = $user->attendancesSummary->pluck('date')->toArray();
            $totalAbsent = collect($periods)
                ->reject(function ($period) {
                    return $period->greaterThanOrEqualTo(Carbon::today());
                })->reject(function ($period) use ($leaveDates, $leavePeriods) {
                    return in_array($period->format('Y-m-d'), $leaveDates);
                })->reject(function ($period) use ($attendedDates) {
                    return in_array($period->format('Y-m-d'), $attendedDates);
                })->reject(function ($period) use ($employeeHolidayDates, $weekHoliday) {
                    if (in_array($period->format('Y-m-d'), $employeeHolidayDates)) {
                        return true;
                    }
                    return $period?->dayName === $weekHoliday?->day;
                })->count();

            return [
                'id' => $user->id,
                'user_nip' => $user->nip,
                'user_name' => $user->name,
                'profile_pic' => $user->profile_pic,
                'role' => $user->roles[0]->name ?? '',
                'total_minutes_late' => number_format($totalMinutesLate),
                'total_not_check_in' => $totalNotCheckIn,
                'total_not_check_out' => $totalNotCheckOut,
                'total_present' => $totalPresent . '/' . $totalPeriodOfWork,
                'total_leaves' => $totalLeaves,
                'total_sick' => $totalSick,
                'total_permission' => $totalPermission,
                'total_absent' => $totalAbsent,
                'total_important_leaves' => $totalImportantLeaves,
                'total_overtime' => $totalOvertime,
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

            if ($actualCheckIn->greaterThan($checkInToUse)) {
                $lateSeconds = $checkInToUse->diffInSeconds($actualCheckIn, false);
                    $totalLate += $lateSeconds;
            }
        }
        return $totalLate / 60;
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


        $users = $query->get();
        $weeklyLatenessMap = [];

        foreach ($users as $user) {
            $attendancesGroupedByWeek = collect($user->attendancesSummary)
                ->groupBy(function ($item) use ($startDate) {
                    $date = Carbon::parse($item['attendancesDate']);
                    $diffInDays = $startDate->diffInDays($date);
                    $groupNumber = floor($diffInDays / 7);
                    return $startDate->copy()->addDays($groupNumber * 7 + 6)->toDateString();
                });

            foreach ($attendancesGroupedByWeek as $weekEndDate => $attendances) {
                $weekLatenessTotal = 0;

                foreach ($attendances as $attendance) {
                    $workTime = $attendance->workTime;
                    if (!$workTime || !$attendance->clock_in) continue;

                    $expectedCheckIn = Carbon::parse("{$attendance->date} {$workTime->clock_in}");
                    $actualCheckIn = Carbon::parse($attendance->clock_in);

                    if ($workTime->name === "Malam") {
                        $expectedCheckIn->addDay();
                    }

                    if ($actualCheckIn->greaterThan($expectedCheckIn)) {
                        $lateness = $expectedCheckIn->diffInSeconds($actualCheckIn);
                        $weekLatenessTotal += $lateness;
                    }
                }

                if ($weekLatenessTotal > 900) {
                    $weeklyLatenessMap[$user->id][$weekEndDate] = $weekLatenessTotal / 60;
                }
            }
        }


        $filter = AttendanceQueryFilter::apply($query, $request);
        $aclFilter = AttendancesACLFilter::apply($filter, $request);
        $attendanceSummary = $aclFilter->paginate(self::$perPage)->onEachSide(1);
        return self::formattedData($weeklyLatenessMap, $attendanceSummary, $startDate, $endDate);
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


        $query = User::with([
            'attendancesSummary' => function ($query) use ($startDate, $endDate, $request) {
                $query->whereBetween('date', [$startDate, $endDate]);
            },
            'employeeSchedules' => function ($query) {
                $query->select('employee_id', 'start_date', 'status')
                    ->where('start_date', '<=', $this->startDate)
                    ->orderBy('start_date', 'asc');
            },
        ], 'branch');


        if(!empty($search)){
          $query->where('name', 'like', '%' . $search . '%');
        }


        $realQuery =  AttendancesACLFilter::apply($query, $request);


        $users = $realQuery->get();
        $weeklyLatenessMap = [];

        foreach ($users as $user) {
            $attendancesGroupedByWeek = collect($user->attendancesSummary)
                ->groupBy(function ($attendance) {
                    return Carbon::parse($attendance->date)->endOfWeek()->format('Y-m-d');
                });

            foreach ($attendancesGroupedByWeek as $weekEndDate => $attendances) {
                $weekLatenessTotal = 0;

                foreach ($attendances as $attendance) {

                    $user = User::where('absent_id', $attendance->employee_id)->first();
                    $employeeSchedule = EmployeeSchedule::where('employee_id', $user->absent_id)
                    ->whereDate('start_date', $attendance->date)
                    ->first();
                    $weekHoliday = WeekHoliday::where('user_id', $user->id)->first();


                    $workTime = $attendance->workTime;
                    if (!$workTime || !$attendance->clock_in) continue;

                    $expectedCheckIn = Carbon::parse("{$attendance->date} {$workTime->clock_in}");
                    $actualCheckIn = Carbon::parse($attendance->clock_in);

                    if ($workTime->name === "Malam") {
                        $expectedCheckIn->addDay();
                    }




                    if ($actualCheckIn->greaterThan($expectedCheckIn)) {
                        $lateness = $expectedCheckIn->diffInSeconds($actualCheckIn);
                        if($employeeSchedule?->status !== 'L' || $weekHoliday->day !== Carbon::parse($attendance->date)->dayName)
                        $weekLatenessTotal += $lateness;
                    }
                }

                if ($weekLatenessTotal > 900) {
                    $weeklyLatenessMap[$user->id][$weekEndDate] = $weekLatenessTotal / 60;
                }
            }
        }


        return self::formattedData($weeklyLatenessMap, $query->paginate(self::$perPage)->onEachSide(1), $startDate, $endDate);
    }

    private function calculateOvertime($user, $startDate, $endDate, string $type)
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
}
