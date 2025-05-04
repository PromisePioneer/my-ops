<?php

namespace App\Support\Attendances;

use App\Http\Requests\AttendancesSummaryFilterByDateRequest;
use App\Models\AttendancesSummary;
use App\Models\EmployeeSchedule;
use App\Models\LeaveAndPermission;
use App\Models\User;
use App\Models\WeekHoliday;
use App\Models\WorkTime;
use App\Support\HelperService\FinancialClosePeriodService;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class AttendanceSummaryDetailService
{
    private FinancialClosePeriodService $financialClosePeriodService;

    public function __construct()
    {
        $this->financialClosePeriodService = new FinancialClosePeriodService();
    }

    public static function concatenateExpectedCheckInTime($attendance, $userWorkTime): string
    {
        $expectedCheckInTime = $userWorkTime->clock_in;
        return Carbon::parse($attendance->timestamp)->format('Y-m-d') . ' ' . $expectedCheckInTime;
    }


    public function getAttendancesSummaryDetail($empId, $startDate, $endDate)
    {
        return AttendancesSummary::with('user')
            ->where('employee_id', $empId)
            ->whereBetween('date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->orderBy('date', 'asc')
            ->get()
            ->keyBy('date');
    }


    public function data(Request $request, ?int $empId, $startDates = null, $endDates = null)
    {

        $startDate = $startDates ?? $this->financialClosePeriodService->startDate();
        $endDate = $endDates ?? $this->financialClosePeriodService->endDate();
        $attendancesData = $this->getAttendancesSummaryDetail($empId, $startDate, $endDate);

        $employeeSchedule = EmployeeSchedule::where('employee_id', $empId)
            ->whereBetween('start_date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])->orderBy('start_date', 'asc')->get()->keyBy('start_date');

        $user = User::where('absent_id', $empId)->first();
        $weekHoliday = WeekHoliday::where('user_id', $user->id)->first();
        $period = CarbonPeriod::create($startDate, $endDate);
        $getLeaves = $this->getLeaves($user, $startDate, $endDate);
        $getSick = $this->getSick($user, $startDate, $endDate);
        $getPermission = $this->getPermission($user, $startDate, $endDate);
        $getImportantLeaves = $this->getImportantLeaves($user, $startDate, $endDate);
        $getOvertimes = $this->getOvertimes($user, $startDate, $endDate);


        $dates = [];
        foreach ($period as $date) {
            $formattedDate = $date->format('Y-m-d');
            $leaveDetails = $getLeaves[$formattedDate] ?? null;
            $sickDetails = $getSick[$formattedDate] ?? null;
            $permissionDetails = $getPermission[$formattedDate] ?? null;
            $importantLeavesDetails = $getImportantLeaves[$formattedDate] ?? null;
            $overtimesDetails = $getOvertimes[$formattedDate] ?? null;
            $dates[$formattedDate] = collect([
                'attendancesDate' => $formattedDate,
                'attendanceData' => $attendancesData->get($formattedDate),
                'employeeSchedule' => $employeeSchedule->get($formattedDate),
                'leaves' => $leaveDetails,
                'sick' => $sickDetails,
                'permission' => $permissionDetails,
                'importantLeaves' => $importantLeavesDetails,
                'overtimes' => $overtimesDetails,
            ]);
        }

        $weeklyAttendances = collect($dates)->groupBy(function ($item) use ($startDate) {
            $date = Carbon::parse($item['attendancesDate']);
            $diffInDays = $startDate->diffInDays($date);
            $groupNumber = floor($diffInDays / 7);
            return $startDate->copy()->addDays($groupNumber * 7 + 6)->toDateString();
        });


        $weekLatenessMap = [];
        foreach ($weeklyAttendances as $weekEndDate => $weekDays) {
            $weekLatenessTotal = 0;
            $weekLatenessDetails = [];

            foreach ($weekDays as $day) {
                $userWorktime = null;
                if (isset($day['attendanceData'])) {
                    $userWorktime = WorkTime::where('id', $day['attendanceData']->work_time_id)->first();
                }

                if (!empty($userWorktime) && !empty($day['attendanceData']?->clock_in)) {
                    $workDate = $day['attendanceData']->date;
                    $expectedCheckIn = Carbon::parse("$workDate {$userWorktime->clock_in}");
                    $actualCheckIn = Carbon::parse($day['attendanceData']->clock_in);

                    $newExpectedCheckIn = null;
                    if ($userWorktime->name === "Malam") {
                        $newExpectedCheckIn = $expectedCheckIn->copy()->addDay();
                    }

                    if ($actualCheckIn->greaterThan($newExpectedCheckIn ?? $expectedCheckIn)) {
                        $lateness = $newExpectedCheckIn ?
                            $newExpectedCheckIn->diffInSeconds($actualCheckIn) :
                            $expectedCheckIn->diffInSeconds($actualCheckIn);
                            if($day['employeeSchedule']?->status !== 'L' || $weekHoliday->day !== Carbon::parse($day['attendancesDate'])->dayName){
                                $weekLatenessDetails[$day['attendancesDate']] = $lateness;
                                $weekLatenessTotal += $lateness;
                            }

                    }
                }
            }

            // Then decide if we should count lateness for this week
            if ($weekLatenessTotal > 900) {
                $weekLatenessMap[$weekEndDate] = number_format($weekLatenessTotal / 60);
            }
        }

        $weeklyLateness = $weekLatenessMap;
        return self::formattedData(collect($dates), $weeklyLateness, $empId, $startDate);
    }

    public function formattedData($attendanceSummary, $weeklyLatenessMap, $empId, $startDate)
    {
        return $attendanceSummary->map(function ($item) use ($empId, $weeklyLatenessMap, $startDate) {
            $userWorktime = WorkTime::where('id', $item['attendanceData']?->work_time_id)->first()
                ?? null;

            $user = User::where('absent_id', $empId)->first();
            $weekHoliday = WeekHoliday::where('user_id', $user->id)->where('day', Carbon::parse($item['attendancesDate'])->dayName)->first();
            $isHoliday = $weekHoliday?->is_holiday ? 'L' : 'H';
            $empSchedule = $item['employeeSchedule']?->status ?? $isHoliday;
            $attendanceDate = Carbon::parse($item['attendancesDate']);
            $diffInDays = $startDate->diffInDays($attendanceDate);
            $groupNumber = floor($diffInDays / 7);
            $weekEndDate = $startDate->copy()->addDays($groupNumber * 7 + 6)->toDateString();


            $totalWeeklyLateness = $weeklyLatenessMap[$weekEndDate] ?? 0;

            return [
                'id' => $item['attendanceData']?->id,
                'weekly_lateness' => $item['attendancesDate'] == $weekEndDate ? $totalWeeklyLateness : null,
                'date_period' => $item['attendancesDate'],
                'clock_in' => Carbon::make($item['attendanceData']?->clock_in)?->format('d/m/Y H:i:s') ?? null,
                'clock_out' => Carbon::make($item['attendanceData']?->clock_out)?->format('d/m/Y H:i:s') ?? null,
                'late' => $this->calculateLate($item, $userWorktime) ?? null,
                'work_time' => $userWorktime->name ?? null,
                'schedule' => $empSchedule,
                'leaves' => $item['leaves'] ?? null,
                'sick' => $item['sick'] ?? null,
                'permission' => $item['permission'] ?? null,
                'important_leaves' => $item['importantLeaves'] ?? null,
                'overtimes' => $item['overtimes'] ?? null
            ];
        });
    }


    public function calculateWeeklyLateTotal($value, $userWorktime = null)
    {
        $total = 0;
        if (!empty($userWorktime) && !empty($value['attendanceData']?->clock_in)) {
            $workDate = $value['attendanceData']?->date;
            $expectedCheckIn = Carbon::parse("$workDate {$userWorktime->clock_in}");
            $actualCheckIn = Carbon::parse($value['attendanceData']?->clock_in);


            $newExpectedCheckIn = null;
            if ($userWorktime->name === "Malam") {
                $newExpectedCheckIn = $expectedCheckIn->copy()->addDays();
            }

            if ($actualCheckIn->greaterThan($newExpectedCheckIn ?? $expectedCheckIn)) {
                $lateness = $newExpectedCheckIn ? $newExpectedCheckIn->diffInSeconds($actualCheckIn) : $expectedCheckIn->diffInSeconds($actualCheckIn);
                if ($lateness > 900) {
                    $total += $lateness;
                }
            }
        }


        return number_format($total / 60);

    }


    public function getPermission($user, $startDate, $endDate): array
    {
        $sick = LeaveAndPermission::where('user_id', $user->id)
            ->where('leaves_status', 'Izin')
            ->where('confirmation_status', 'Diterima')
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('start_date', [$startDate, $endDate])
                    ->orWhereBetween('end_date', [$startDate, $endDate]);
            })
            ->get();

        $permissionPeriod = [];

        foreach ($sick as $dates) {
            $permissionPeriod = array_merge(
                $permissionPeriod,
                CarbonPeriod::create($dates->start_date, $dates->end_date)->toArray()
            );
        }

        $sick = [];
        foreach ($permissionPeriod as $date) {
            $formattedDate = Carbon::parse($date)->format('Y-m-d');
            $sick[$formattedDate] = collect([
                'permission_date' => $formattedDate,
                'status' => 'Izin',
            ]);
        }

        return $sick;
    }


    public function getSick($user, $startDate, $endDate)
    {
        $sick = LeaveAndPermission::where('user_id', $user->id)
            ->where('leaves_status', 'Sakit')
            ->where('confirmation_status', 'Diterima')
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('start_date', [$startDate, $endDate])
                    ->orWhereBetween('end_date', [$startDate, $endDate]);
            })
            ->get();

        $sickPeriod = [];

        foreach ($sick as $dates) {
            $sickPeriod = array_merge(
                $sickPeriod,
                CarbonPeriod::create($dates->start_date, $dates->end_date)->toArray()
            );
        }

        $sick = [];
        foreach ($sickPeriod as $date) {
            $formattedDate = Carbon::parse($date)->format('Y-m-d');
            $sick[$formattedDate] = collect([
                'sick_date' => $formattedDate,
                'status' => 'Sakit',
            ]);
        }


        return $sick;
    }


    public function getImportantLeaves($user, $startDate, $endDate): array
    {
        $leaveAndPermission = LeaveAndPermission::where('user_id', $user->id)
            ->where('leaves_status', 'Cuti Penting')
            ->where('confirmation_status', 'Diterima')
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('start_date', [$startDate, $endDate])
                    ->orWhereBetween('end_date', [$startDate, $endDate]);
            })->get();


        $importantLeavesPeriod = [];

        foreach ($leaveAndPermission as $dates) {
            $importantLeavesPeriod = array_merge(
                $importantLeavesPeriod,
                CarbonPeriod::create($dates->start_date, $dates->end_date)->toArray()
            );
        }


        $importantLeaves = [];
        foreach ($importantLeavesPeriod as $date) {
            $formattedDate = Carbon::parse($date)->format('Y-m-d');
            $importantLeaves[$formattedDate] = collect([
                'leaves_date' => $formattedDate,
                'status' => 'Cuti Penting',
            ]);
        }

        return $importantLeaves;
    }


    public function getLeaves($user, $startDate, $endDate): array
    {
        $leaveAndPermission = LeaveAndPermission::where('user_id', $user->id)
            ->where('leaves_status', 'Cuti')
            ->where('confirmation_status', 'Diterima')
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('start_date', [$startDate, $endDate])
                    ->orWhereBetween('end_date', [$startDate, $endDate]);
            })->get();


        $leavePeriods = [];

        foreach ($leaveAndPermission as $dates) {
            $leavePeriods = array_merge(
                $leavePeriods,
                CarbonPeriod::create($dates->start_date, $dates->end_date)->toArray()
            );
        }

        $leaves = [];
        foreach ($leavePeriods as $date) {
            $formattedDate = Carbon::parse($date)->format('Y-m-d');
            $leaves[$formattedDate] = collect([
                'leaves_date' => $formattedDate,
                'status' => 'Cuti',
            ]);
        }

        return $leaves;
    }


    public function calculateLate($item, $userWorktime = null): ?string
    {
        if (!empty($userWorktime) && !empty($item['attendanceData']?->clock_in)) {

            $workDate = $item['attendanceData']?->date;
            $expectedCheckIn = Carbon::parse("$workDate {$userWorktime->clock_in}");
            $actualCheckIn = Carbon::parse($item['attendanceData']?->clock_in);


            $newExpectedCheckIn = null;
            if ($userWorktime->name === "Malam") {
                $newExpectedCheckIn = $expectedCheckIn->copy()->addDays();
            }

            if ($actualCheckIn->greaterThan($newExpectedCheckIn ?? $expectedCheckIn)) {
                $lateness = $newExpectedCheckIn ? $newExpectedCheckIn->diffInSeconds($actualCheckIn) : $expectedCheckIn->diffInSeconds($actualCheckIn);
                return number_format($lateness / 60);
            }
        }
        return null;
    }


    public function filterByDate(AttendancesSummaryFilterByDateRequest $request, User $user)
    {
        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);


        $attendancesData = AttendancesSummary::with('user')
            ->where('employee_id', $user->absent_id)
            ->whereBetween('date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->orderBy('date', 'asc')
            ->get()
            ->keyBy('date');


        $employeeSchedule = EmployeeSchedule::where('employee_id', $user->absent_id)
            ->whereBetween('start_date', [$startDate, $endDate])->orderBy('start_date', 'asc')->get()->keyBy('start_date');

        $user = User::where('absent_id', $user->absent_id)->first();
        $period = CarbonPeriod::create($startDate, $endDate);
        $getLeaves = $this->getLeaves($user, $startDate, $endDate);
        $getSick = $this->getSick($user, $startDate, $endDate);
        $getPermission = $this->getPermission($user, $startDate, $endDate);
        $getImportantLeaves = $this->getImportantLeaves($user, $startDate, $endDate);
        $getOvertimes = $this->getOvertimes($user, $startDate, $endDate);


        $dates = [];

        foreach ($period as $date) {
            $formattedDate = $date->format('Y-m-d');
            $leaveDetails = $getLeaves[$formattedDate] ?? null;
            $sickDetails = $getSick[$formattedDate] ?? null;
            $permissionDetails = $getPermission[$formattedDate] ?? null;
            $importantLeavesDetails = $getImportantLeaves[$formattedDate] ?? null;
            $overtimesDetails = $getOvertimes[$formattedDate] ?? null;
            $dates[$formattedDate] = collect([
                'attendancesDate' => $formattedDate,
                'attendanceData' => $attendancesData->get($formattedDate),
                'employeeSchedule' => $employeeSchedule->get($formattedDate),
                'leaves' => $leaveDetails,
                'sick' => $sickDetails,
                'permission' => $permissionDetails,
                'importantLeaves' => $importantLeavesDetails,
                'overtimes' => $overtimesDetails,
            ]);
        }


        $weeklyAttendances = collect($dates)->groupBy(function ($item) use ($startDate) {
            $date = Carbon::parse($item['attendancesDate']);
            $diffInDays = $startDate->diffInDays($date);
            $groupNumber = floor($diffInDays / 7);
            return $startDate->copy()->addDays($groupNumber * 7 + 6)->toDateString();
        });


        $weekLatenessMap = [];
        // Calculate weekly lateness for each week
        foreach ($weeklyAttendances as $weekEndDate => $weekDays) {
            $weekLatenessTotal = 0;
            $weekLatenessDetails = [];

            // First collect all lateness values for the week
            foreach ($weekDays as $day) {
                $userWorktime = null;
                if (isset($day['attendanceData'])) {
                    $userWorktime = WorkTime::where('id', $day['attendanceData']->work_time_id)->first();
                }

                if (!empty($userWorktime) && !empty($day['attendanceData']?->clock_in)) {
                    $workDate = $day['attendanceData']->date;
                    $expectedCheckIn = Carbon::parse("$workDate {$userWorktime->clock_in}");
                    $actualCheckIn = Carbon::parse($day['attendanceData']->clock_in);

                    $newExpectedCheckIn = null;
                    if ($userWorktime->name === "Malam") {
                        $newExpectedCheckIn = $expectedCheckIn->copy()->addDay();
                    }

                    if ($actualCheckIn->greaterThan($newExpectedCheckIn ?? $expectedCheckIn)) {
                        $lateness = $newExpectedCheckIn ?
                            $newExpectedCheckIn->diffInSeconds($actualCheckIn) :
                            $expectedCheckIn->diffInSeconds($actualCheckIn);
                        $weekLatenessDetails[$day['attendancesDate']] = $lateness;
                        $weekLatenessTotal += $lateness;
                    }
                }
            }

            // Then decide if we should count lateness for this week
            if ($weekLatenessTotal > 900) {
                $weekLatenessMap[$weekEndDate] = number_format($weekLatenessTotal / 60);
            }
        }

        $weeklyLateness = $weekLatenessMap;


        return self::formattedData(collect($dates), $weeklyLateness, $user->absent_id, $startDate);
    }

    private function getOvertimes($user, mixed $startDate, mixed $endDate): array
    {
        $leaveAndPermission = LeaveAndPermission::where('user_id', $user->id)
            ->where('leaves_status', 'Lembur')
            ->where('confirmation_status', 'Diterima')
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('start_date', [$startDate, $endDate])
                    ->orWhereBetween('end_date', [$startDate, $endDate]);
            })->get();


        $leavePeriods = [];

        foreach ($leaveAndPermission as $dates) {
            $leavePeriods = array_merge(
                $leavePeriods,
                CarbonPeriod::create($dates->start_date, $dates->end_date)->toArray()
            );
        }

        $leaves = [];
        foreach ($leavePeriods as $date) {
            $formattedDate = Carbon::parse($date)->format('Y-m-d');
            $leaves[$formattedDate] = collect([
                'leaves_date' => $formattedDate,
                'status' => 'Lembur',
            ]);
        }

        return $leaves;
    }

}
