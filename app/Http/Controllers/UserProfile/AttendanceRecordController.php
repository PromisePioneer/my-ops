<?php

namespace App\Http\Controllers\UserProfile;

use App\Http\Controllers\Controller;
use App\Models\AttendancesSummary;
use App\Models\User;
use App\Service\HelperService\FinancialClosePeriodService;
use Carbon\CarbonPeriod;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

use function App\Helper\formatDate;

class AttendanceRecordController extends Controller
{

    private FinancialClosePeriodService $financialClosePeriodService;

    public function __construct()
    {
        $this->financialClosePeriodService = new FinancialClosePeriodService();
    }

    public function index(): View
    {
        return view('pages.utilities.user-profile.attendance-records.index');
    }


    public function data(Request $request, User $user = null): JsonResponse
    {
        $startDate = $this->financialClosePeriodService->startDate();
        $endDate = $this->financialClosePeriodService->endDate();

        $attendancesData = AttendancesSummary::with('user')
            ->where('employee_id', $user?->absent_id ?? $request->user()->absent_id)
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

        $data = self::formattedData(collect($dates), $user->absent_id ?? $request->user()->absent_id);

        return response()->json($data);
    }


    private static function formattedData($attendanceSummary, $empId)
    {
        return $attendanceSummary->map(function ($item) use ($empId) {
            return [
                'date_period' => formatDate($item['attendancesDate']),
                'clock_in' => $item['attendanceData']?->clock_in,
                'clock_out' => $item['attendanceData']?->clock_out,
            ];
        });
    }


    public function filter(Request $request, User $user = null): JsonResponse
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');


        $attendancesData = AttendancesSummary::with('user')
            ->where('employee_id', $user->absent_id ?? $request->user()->absent_id)
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

        $data = self::formattedData(collect($dates), $user->absent_id ?? $request->user()->absent_id);
        return response()->json($data);

    }
}
