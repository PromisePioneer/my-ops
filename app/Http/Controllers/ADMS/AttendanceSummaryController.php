<?php

namespace App\Http\Controllers\ADMS;

use App\Http\Controllers\Controller;
use App\Http\Requests\ADMS\AttendancesSummaryAssignSPRequest;
use App\Models\Attendances;
use App\Models\SP;
use App\Models\User;
use App\Models\WorkTime;
use App\Service\SpService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AttendanceSummaryController extends Controller
{

    public readonly int $perPage;
    private Attendances $attendances;
    private SpService $spService;

    public function __construct()
    {
        $this->attendances = new Attendances();
        $this->spService = new SpService();
        $this->perPage = 10;
    }

    public function index(): View
    {
        return view('pages.adms.attendances-summary.index');
    }


    public function selectPeriodData(): JsonResponse
    {
        return response()->json($this->attendances->getAttendancesPeriod($this->perPage));
    }


    public function detail($time): View
    {
        $month = date('m', strtotime($time));
        $year = date('Y', strtotime($time));
        return view('pages.adms.attendances-summary.detail', compact('month', 'year'));
    }

    public function detailData(Request $request, $month, $year): JsonResponse
    {
        return response()->json($this->attendances->getAttendancesBasedOnPeriod($month, $year, $this->perPage));
    }

    public function searchDetailData(Request $request): JsonResponse
    {
        return response()->json($this->attendances->searchAttendancesSummary($request));
    }


    public function filterDate(Request $request, $month, $year): JsonResponse
    {
        return response()->json($this->attendances->filterAttendancesSummaryByDate($request->start_date,
            $request->end_date, $month, $year, $this->perPage));
    }


    public function assignSPToEmployee(
        AttendancesSummaryAssignSPRequest $request,
        $employeeId
    ): JsonResponse {
        $user = User::where('users.absent_id', $employeeId)
            ->first();

        $data = $request->validated();
        $data['user_id'] = $user->id;
        $data['sp_number'] = $this->spService->generateSpNumber($request);
        $data['created_by'] = $request->user()->id;
        SP::create($data);

        return response()->json([
            'message' => 'data berhasil disimpan'
        ]);
    }


    public function attendanceSummaryDetailForOneMonthBasedOnUserId($month, $year, $employeeId): JsonResponse
    {
        $attendances = $this->attendances->attendanceSummaryDetailForOneMonthBasedOnUserId($month, $year,
            $employeeId, $this->perPage);


        $totalPresentAndTotalMinutesLate = Attendances::select('attendances.employee_id', 'users.name as user_name',
            'attendances.timestamp', 'attendances.status1', 'work_time.name as work_time', 'users.nip as user_nip')
            ->join('users', 'users.absent_id', '=', 'attendances.employee_id')
            ->leftJoin('user_work_time', 'user_work_time.user_id', '=', 'users.id')
            ->leftJoin('work_time', 'work_time.id', '=', 'user_work_time.work_time_id')
            ->whereMonth('attendances.timestamp', $month)
            ->whereYear('attendances.timestamp', $year)
            ->where('users.absent_id', $employeeId)
            ->get()
            ->groupBy(function ($item) {
                return $item->employee_id.'-'.Carbon::parse($item->timestamp)->format('Y-m-d');
            });

        $summaryData = $totalPresentAndTotalMinutesLate->map(function ($items) {
            $totalMinutesLate = 0;
            $totalPresent = $items; // Menghitung hari hadir (check-in)


            dd($totalPresent);

            // Mengelompokkan per hari untuk menghitung keterlambatan harian
            $dailyAttendances = $items->groupBy(function ($item) {
                return Carbon::parse($item->timestamp)->format('Y-m-d');
            });

            foreach ($dailyAttendances as $day => $dailyItems) {
                $checkIn = $dailyItems->where('status1', 0)->first(); // Data Check-In

                if ($checkIn) { // Memastikan ada data check-in
                    $userWorktime = WorkTime::where('name', $checkIn->work_time)->first();
                    $defaultWorkTime = WorkTime::where('id', 1)->first();
                    $expectedCheckInTime = $userWorktime ? $userWorktime->clock_in : $defaultWorkTime->clock_in;

                    // Gabungkan tanggal check-in dengan waktu yang diharapkan
                    $expectedCheckIn = Carbon::parse($checkIn->timestamp)->format('Y-m-d').' '.$expectedCheckInTime;
                    $expectedCheckIn = Carbon::parse($expectedCheckIn);

                    // Hitung keterlambatan dalam menit untuk hari tersebut
                    $actualCheckIn = Carbon::parse($checkIn->timestamp);
                    if ($actualCheckIn->greaterThan($expectedCheckIn)) {
                        $minutesLate = $expectedCheckIn->diffInMinutes($actualCheckIn);
                        $totalMinutesLate += $minutesLate;
                    }
                }
            }

            return [
                'totalMinutesLate' => (int) $totalMinutesLate.' Menit',
                'total_present' => $totalPresent.' Hari',
                'name' => $items->first()->user_name,
                'nik' => $items->first()->user_nip
            ];
        })->values();


        return response()->json([
            'data' => $attendances,
            'summary_data' => $summaryData[0]
        ]);
    }
}
