<?php

namespace App\Http\Controllers\HRIS\Payroll;

use App\Http\Controllers\Controller;
use App\Http\Requests\Payroll\GeneratePayrollRequest;
use App\Models\Attendances;
use App\Models\GeneratePayroll;
use App\Models\PayrollSchedule;
use App\Models\User;
use App\Service\Attendances\AttendancesSummaryService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class GeneratePayrollController extends Controller
{

    private Attendances $attendance;
    private AttendancesSummaryService $attendanceSummaryService;

    public function __construct()
    {
        $this->attendance = new Attendances();
        $this->attendanceSummaryService = new AttendancesSummaryService();
    }

    public function index(): View
    {
        $payrollSchedule = PayrollSchedule::first();
        return view('pages.payroll.generate-payroll.index', compact('payrollSchedule'));
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
}
