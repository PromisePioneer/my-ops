<?php

namespace App\Service\Attendances;

use App\Models\AttendancesSummary;
use App\Models\EmployeeSchedule;
use App\Models\LeaveAndPermission;
use App\Models\User;
use App\Models\WorkTime;
use App\Service\HelperService\FinancialClosePeriodService;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;

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


    public function data(Request $request, int $empId)
    {
        $startDate = $this->financialClosePeriodService->startDate();
        $endDate = $this->financialClosePeriodService->endDate();

        $attendancesData = AttendancesSummary::with('user')
            ->where('employee_id', $empId)
            ->whereBetween('date', [$startDate, $endDate])
            ->orderBy('date', 'asc')
            ->get()
            ->keyBy('date');

        $employeeSchedule = EmployeeSchedule::where('employee_id', $empId)
            ->whereBetween('date', [$startDate, $endDate])->orderBy('date', 'asc')->get()->keyBy('date');

        $user = User::where('absent_id', $empId)->first();
        $period = CarbonPeriod::create($startDate, $endDate);
        $getLeaves = $this->getLeaves($user, $startDate, $endDate);
        $getSick = $this->getSick($user, $startDate, $endDate);
        $getPermission = $this->getPermission($user, $startDate, $endDate);


        $dates = [];
        foreach ($period as $date) {
            $formattedDate = $date->format('Y-m-d');
            $leaveDetails = $getLeaves[$formattedDate] ?? null;
            $sickDetails = $getSick[$formattedDate] ?? null;
            $permissionDetails = $getPermission[$formattedDate] ?? null;
            $dates[$formattedDate] = collect([
                'attendancesDate' => $formattedDate,
                'attendanceData' => $attendancesData->get($formattedDate),
                'employeeSchedule' => $employeeSchedule->get($formattedDate),
                'leaves' => $leaveDetails,
                'sick' => $sickDetails,
                'permission' => $permissionDetails,
            ]);
        }

        return self::formattedData(collect($dates), $empId);
    }

    public function formattedData($attendanceSummary, $empId)
    {
        return $attendanceSummary->map(function ($item) use ($empId) {


            $userWorktime = WorkTime::where('id', $item['attendanceData']?->work_time_id)->first()
                ?? WorkTime::find(1);


            return [
                'date_period' => $item['attendancesDate'],
                'clock_in' => Carbon::make($item['attendanceData']?->clock_in)?->format('d/m/Y H:i:s') ?? null,
                'clock_out' => Carbon::make($item['attendanceData']?->clock_out)?->format('d/m/Y H:i:s') ?? null,
                'late' => $this->calculateLate($item, $userWorktime),
                'work_time' => $userWorktime->name ?? null,
                'schedule' => $item['employeeSchedule']?->status ?? null,
                'leaves' => $item['leaves'] ?? null,
                'sick' => $item['sick'] ?? null,
                'permission' => $item['permission'] ?? null,
            ];
        });
    }


    public function getPermission($user, $startDate, $endDate): array
    {
        $sick = LeaveAndPermission::where('user_id', $user->id)
            ->where('leaves_status', 'Izin')
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

    public function getLeaves($user, $startDate, $endDate)
    {
        $leaveAndPermission = LeaveAndPermission::where('user_id', $user->id)
            ->where('leaves_status', 'Cuti')
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('start_date', [$startDate, $endDate])
                    ->orWhereBetween('end_date', [$startDate, $endDate]);
            })
            ->get();

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
            $actualCheckIn = Carbon::parse($item['attendanceData']?->clock_in);

            $workDate = $item['attendanceData']?->date;
            $expectedCheckIn = Carbon::parse("$workDate {$userWorktime->clock_in}");


            if ($expectedCheckIn->lessThan($actualCheckIn) && $expectedCheckIn->toTimeString() === "00:00:00") {
                $expectedCheckIn->addDays(2);
            }

            if ($actualCheckIn->greaterThan($expectedCheckIn)) {
                $lateness = $expectedCheckIn->diffInMinutes($actualCheckIn);
                return "$lateness menit";
            }
        }

        return null;
    }


    public function filterByDate(Request $request, User $user)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;


        $attendancesData = AttendancesSummary::with('user')
            ->where('employee_id', $user->absent_id)
            ->whereBetween('date', [$startDate, $endDate])
            ->orderBy('date', 'asc')
            ->get()
            ->keyBy('date');

        $period = CarbonPeriod::create($startDate, $endDate);

        $dates = [];

        foreach ($period as $date) {
            $formattedDate = $date->format('Y-m-d');
            $dates[$formattedDate] = collect([
                'attendancesDate' => $formattedDate,
                'attendanceData' => $attendancesData->get($formattedDate),
            ]);
        }

        return self::formattedData(collect($dates), $user->absent_id);
    }

}
