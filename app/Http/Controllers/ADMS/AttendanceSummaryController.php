<?php

namespace App\Http\Controllers\ADMS;

use App\Http\Controllers\Controller;
use App\Models\Attendances;
use App\Models\WorkTime;
use App\Service\AttendancesSummaryService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AttendanceSummaryController extends Controller
{
    public readonly int $perPage;
    private AttendancesSummaryService $attendanceSummaryService;

    public function __construct()
    {
        $this->perPage = 10;
        $this->attendanceSummaryService = new AttendancesSummaryService();
    }

    public function index(): View
    {
        return view('pages.adms.attendances-summary.index');
    }

    public function selectPeriodData(): JsonResponse
    {
        return response()->json($this->attendanceSummaryService->attendancesPeriod());
    }

    public function detail($time): View
    {
        $month = date('m', strtotime($time));
        $year = date('Y', strtotime($time));

        return view('pages.adms.attendances-summary.detail', compact('month', 'year'));
    }

    public function detailData($month, $year): JsonResponse
    {
        return response()->json($this->attendanceSummaryService->attendancesDataInAMonth($month, $year));
    }

    public function searchDetailData(Request $request, $month, $year): JsonResponse
    {
        return response()->json($this->attendanceSummaryService->searchAttendancesSummary($request, $month, $year));
    }

    public function filterDate(Request $request, $month, $year): JsonResponse
    {
        return response()->json($this->attendanceSummaryService->filterAttendancesSummaryByDate(
            $request->start_date,
            $request->end_date,
            $month,
            $year,
        ));
    }

    public function attendanceSummaryDetailForOneMonthBasedOnUserId(
        Attendances $attendances,
        string $month,
        string $year,
        string $employeeId
    ): JsonResponse {
        $attendancesData = $attendances->attendanceSummaryDetailForOneMonthBasedOnUserId(
            $month,
            $year,
            $employeeId,
            10
        );

        $totalPresentAndTotalMinutesLate = DB::table('attendances')->select(
            'attendances.employee_id',
            'users.name as user_name',
            'attendances.timestamp',
            'attendances.status1',
            'work_time.name as work_time',
            'users.nip as user_nip'
        )
            ->join('users', 'users.absent_id', '=', 'attendances.employee_id')
            ->where('users.absent_id', $employeeId)
            ->leftJoin('user_work_time', 'user_work_time.user_id', '=', 'users.id')
            ->leftJoin('work_time', 'work_time.id', '=', 'user_work_time.work_time_id')
            ->whereMonth('attendances.timestamp', $month)
            ->whereYear('attendances.timestamp', $year)
            ->get()
            ->groupBy('user_name');

        $summaryData = $totalPresentAndTotalMinutesLate->map(function ($items) {
            $totalMinutesLate = 0;
            $dailyAttendances = $items->groupBy(function ($item) {
                return $item->employee_id.'-'.Carbon::parse($item->timestamp)->format('Y-m-d');
            });

            foreach ($dailyAttendances as $day => $dailyItems) {
                $checkIn = $dailyItems->where('status1', 0)->first();
                if ($checkIn) {
                    $userWorktime = WorkTime::where('name', $checkIn->work_time ?? null)->first();
                    $defaultWorkTime = WorkTime::where('id', 1)->first();
                    $expectedCheckInTime = $userWorktime ? $userWorktime->clock_in : $defaultWorkTime->clock_in;

                    $expectedCheckIn = Carbon::parse($checkIn->timestamp)->format('Y-m-d').' '.$expectedCheckInTime;
                    $expectedCheckIn = Carbon::parse($expectedCheckIn);

                    // Calculate lateness in minutes for that day
                    $actualCheckIn = Carbon::parse($checkIn->timestamp);
                    if ($actualCheckIn->greaterThan($expectedCheckIn)) {
                        $minutesLate = $expectedCheckIn->diffInMinutes($actualCheckIn);
                        $totalMinutesLate += $minutesLate;
                    }
                }
            }

            return [
                'totalMinutesLate' => (int) $totalMinutesLate.' Menit',
                'name' => $items->first()->user_name,
                'nik' => $items->first()->user_nip,
                'total_present' => $items->where('status1', 0)->count(),
            ];
        })->values();

        return response()->json([
            'data' => $attendancesData,
            'summary_data' => $summaryData[0],
        ]);
    }
}
