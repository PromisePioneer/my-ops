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

        $dates = [];
        foreach ($period as $date) {
            $formattedDate = $date->format('Y-m-d');
            $dates[$formattedDate] = collect([
                'attendancesDate' => $formattedDate,
                'attendanceData' => $attendancesData->get($formattedDate),
                'employeeSchedule' => $employeeSchedule->get($formattedDate),
                'leaves' => $getLeaves
            ]);
        }

        return self::formattedData(collect($dates), $empId);
    }

    public function formattedData($attendanceSummary, $empId)
    {
        return $attendanceSummary->map(function ($item) use ($empId) {
            $userWorktime = WorkTime::where('id', $item['attendanceData']?->work_time_id)->first()
                ?? null;

//            dd($item['leaves']);

            return [
                'date_period' => $item['attendancesDate'],
                'clock_in' => $item['attendanceData']?->clock_in,
                'clock_out' => $item['attendanceData']?->clock_out,
                'late' => $this->calculateLate($item, $userWorktime) ?? null,
                'work_time' => $userWorktime->name ?? '',
                'schedule' => $item['employeeSchedule']?->status,
            ];
        });
    }


    public function getLeaves($user, $startDate, $endDate)
    {
        $leaveAndPermission = LeaveAndPermission::where('user_id', $user->id)
            ->where('leaves_status', 'Cuti')
            ->whereBetween('start_date', [$startDate, $endDate])
            ->orWhereBetween('end_date', [$startDate, $endDate])
            ->get();

        foreach ($leaveAndPermission as $dates) {
            $leavePeriod = CarbonPeriod::create($dates->start_date, $dates->end_date);
        }

        $leavesDate = [];

        foreach ($leavePeriod as $date) {
            $leavesDate[] = $date->format('Y-m-d');
        }

        $leaves = [];
        foreach ($leavesDate as $date) {
            $leaves[$date] = collect([
                'leaves_date' => $date,
                'status' => 'Cuti'
            ]);
        }


        return $leaves;
    }


    public function calculateLate($item, $userWorktime = null): null|string
    {
        if (!empty($userWorktime)) {
            $expectedCheckIn = Carbon::parse($item['attendancesDate'])
                    ->format('Y-m-d') . ' ' . $userWorktime->clock_in;
            $actualCheckIn = Carbon::parse($item['attendancesDate'])
                    ->format('Y-m-d') . ' ' . $item['attendanceData']?->clock_in;

            $parseExpectedCheckIn = Carbon::parse($expectedCheckIn);
            $parseActualCheckIn = Carbon::parse($actualCheckIn);

            if ($parseActualCheckIn->greaterThan($parseExpectedCheckIn)) {
                return Carbon::parse($expectedCheckIn)->diffInMinutes(Carbon::parse($actualCheckIn)) . ' Menit';
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
