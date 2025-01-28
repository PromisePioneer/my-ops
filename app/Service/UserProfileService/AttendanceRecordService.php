<?php

namespace App\Service\UserProfileService;

use AllowDynamicProperties;
use App\Models\AttendancesSummary;
use App\Models\EmployeeSchedule;
use App\Models\LeaveAndPermission;
use App\Models\User;
use App\Models\WorkTime;
use App\Service\HelperService\FinancialClosePeriodService;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;

#[AllowDynamicProperties] class AttendanceRecordService
{

    public function __construct()
    {
        $this->financialClosePeriodService = new FinancialClosePeriodService();
    }


    public function data(Request $request, ?User $user)
    {
        $startDate = $this->financialClosePeriodService->startDate();
        $endDate = $this->financialClosePeriodService->endDate();


        $employeeSchedule = EmployeeSchedule::where('employee_id', $user?->absent_id ?? $request->user()->absent_id)
            ->whereBetween('start_date', [$startDate, $endDate])->orderBy('start_date', 'asc')->get()->keyBy('start_date');


        $attendancesData = AttendancesSummary::with('user')
            ->where('employee_id', $user?->absent_id ?? $request->user()->absent_id)
            ->whereBetween('date', [$startDate, $endDate])
            ->orderBy('date', 'asc')
            ->get()
            ->keyBy('date');


        $getLeaves = $this->getLeaves($request, $user, $startDate, $endDate);
        $getSick = $this->getSick($request, $user, $startDate, $endDate);
        $getPermission = $this->getPermission($request, $user, $startDate, $endDate);


        $period = CarbonPeriod::create($startDate, $endDate);


        $dates = [];

        foreach ($period as $date) {
            $formattedDate = $date->format('Y-m-d');
            $leaveDetails = $getLeaves[$formattedDate] ?? null;
            $sickDetails = $getSick[$formattedDate] ?? null;
            $permissionDetails = $getPermission[$formattedDate] ?? null;
            $formattedDate = $date->format('Y-m-d');
            $dates[$formattedDate] = collect([
                'attendancesDate' => $formattedDate,
                'attendanceData' => $attendancesData->get($formattedDate),
                'employeeSchedule' => $employeeSchedule->get($formattedDate),
                'leaves' => $leaveDetails,
                'sick' => $sickDetails,
                'permission' => $permissionDetails,
            ]);
        }

        return self::formattedData(collect($dates), $user->absent_id ?? $request->user()->absent_id);
    }

    private static function formattedData($attendanceSummary, $empId)
    {
        return $attendanceSummary->map(function ($item) use ($empId) {

            $userWorktime = WorkTime::where('id', $item['attendanceData']?->work_time_id)->first()
                ?? null;

            return [
                'date_period' => $item['attendancesDate'],
                'clock_in' => $item['attendanceData']?->clock_in,
                'clock_out' => $item['attendanceData']?->clock_out,
                'late' => self::calculateLate($item, $userWorktime) ?? null,
                'work_time' => $userWorktime->name ?? null,
                'schedule' => $item['employeeSchedule']?->status ?? null,
                'leaves' => $item['leaves'] ?? null,
                'sick' => $item['sick'] ?? null,
                'permission' => $item['permission'] ?? null,
            ];
        });
    }

    private static function calculateLate($item, $userWorktime = null): null|string
    {
        if (!empty($userWorktime)) {
            $expectedCheckIn = Carbon::parse($item['attendancesDate'])
                    ->format('Y-m-d') . ' ' . $userWorktime->clock_in;
            $actualCheckIn = Carbon::parse($item['attendanceData']?->clock_in);



            $parseExpectedCheckIn = Carbon::parse($expectedCheckIn);
            $parseActualCheckIn = Carbon::parse($actualCheckIn);




            if ($parseActualCheckIn->greaterThan($parseExpectedCheckIn)) {
                return (int) $parseExpectedCheckIn->diffInMinutes($parseActualCheckIn) . ' Menit';
            }
        }

        return null;
    }


    public function filter(Request $request, ?User $user = null)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');


        $employeeSchedule = EmployeeSchedule::where('employee_id', $user?->absent_id ?? $request->user()->absent_id)
            ->whereBetween('date', [$startDate, $endDate])->orderBy('date', 'asc')->get()->keyBy('date');


        $attendancesData = AttendancesSummary::with('user')
            ->where('employee_id', $user?->absent_id ?? $request->user()->absent_id)
            ->whereBetween('date', [$startDate, $endDate])
            ->orderBy('date', 'asc')
            ->get()
            ->keyBy('date');


        $getLeaves = $this->getLeaves($request, $user, $startDate, $endDate);
        $getSick = $this->getSick($request, $user, $startDate, $endDate);
        $getPermission = $this->getPermission($request, $user, $startDate, $endDate);


        $period = CarbonPeriod::create($startDate, $endDate);


        $dates = [];

        foreach ($period as $date) {
            $formattedDate = $date->format('Y-m-d');
            $leaveDetails = $getLeaves[$formattedDate] ?? null;
            $sickDetails = $getSick[$formattedDate] ?? null;
            $permissionDetails = $getPermission[$formattedDate] ?? null;
            $formattedDate = $date->format('Y-m-d');
            $dates[$formattedDate] = collect([
                'attendancesDate' => $formattedDate,
                'attendanceData' => $attendancesData->get($formattedDate),
                'employeeSchedule' => $employeeSchedule->get($formattedDate),
                'leaves' => $leaveDetails,
                'sick' => $sickDetails,
                'permission' => $permissionDetails,
            ]);
        }
        return self::formattedData(collect($dates), $user->absent_id ?? $request->user()->absent_id);
    }

    public function getPermission(Request $request, $user, $startDate, $endDate): array
    {
        $sick = LeaveAndPermission::where('user_id', $user?->absent_id ?? $request->user()->absent_id)
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


    public function getSick(Request $request, $user, $startDate, $endDate)
    {
        $sick = LeaveAndPermission::where('user_id', $user?->absent_id ?? $request->user()->absent_id)
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

    public function getLeaves(Request $request, $user, $startDate, $endDate): array
    {
        $leaveAndPermission = LeaveAndPermission::where('user_id', $user->absent_id ?? $request->user()->id)
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

}
