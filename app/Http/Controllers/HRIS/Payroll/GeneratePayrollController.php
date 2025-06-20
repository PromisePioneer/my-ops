<?php

namespace App\Http\Controllers\HRIS\Payroll;

use AllowDynamicProperties;
use App\Http\Controllers\Controller;
use App\Http\Requests\Payroll\GeneratePayrollRequest;
use App\Models\GeneratePayroll;
use App\Models\PayrollSchedule;
use App\Models\User;
use App\Support\Attendances\AttendanceSummary\AttendanceSummaryService;
use App\Support\Payroll\GeneratePayrollService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

#[AllowDynamicProperties] class GeneratePayrollController extends Controller
{

    public function __construct()
    {
        $this->attendanceSummaryService = new AttendanceSummaryService();
        $this->generatePayrollService = new GeneratePayrollService();
    }

    public function index(): View
    {
        $payrollSchedule = PayrollSchedule::first();
        return view('pages.payroll.generate-payroll.employee.index', compact('payrollSchedule'));
    }


    public function generatePayroll(GeneratePayrollRequest $request): JsonResponse
    {
        $date = $request->period;
        $month = Carbon::now()->month;
        $year = Carbon::now()->year;

        $attendances = $this->attendance->getAttendancesSummaryInAMonth($month, $year);
        $attendancesSummary = $this->attendanceSummaryService->attendancesDataInAMonthFormattedData(
            $attendances,
            $month,
            $year
        );

        $users = User::with('jobInformation')->get();


        foreach ($users as $user) {
            GeneratePayroll::updateOrCreate([
                'period' => $request->period.' '.Carbon::now()->month,
                'user_id' => $user->id,
            ], [
                'payment_schedule' => Carbon::parse($year.'-'.$month.'-'.$request->period)->format('Y-m-d'),
                'basic_salary' => 2400000,
                'allowances' => 0,
                'published' => false,
            ]);
        }

        return response()->json([
            'message' => 'data berhasil disimpan',
        ]);
    }

    public function getAttendancesSummary(Request $request): JsonResponse
    {
        return response()->json($this->attendanceSummaryService->data($request));
    }


    public function getUserJobInformation(): JsonResponse
    {
        return response()->json($this->generatePayrollService->getUserJobInformation());
    }

}
