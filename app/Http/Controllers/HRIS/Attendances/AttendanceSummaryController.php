<?php

namespace App\Http\Controllers\HRIS\Attendances;

use App\Http\Controllers\Controller;
use App\Http\Requests\AttendanceCorrectionRequest;
use App\Http\Requests\AttendancesSummaryFilterByDateRequest;
use App\Models\AttendancesSummary;
use App\Models\User;
use App\Service\Attendances\AttendancesSummaryService;
use App\Service\Attendances\AttendanceSummaryDetailService;
use Carbon\Carbon;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Jmrashed\Zkteco\Lib\Helper\Attendance;

class AttendanceSummaryController extends Controller
{
    public readonly int $perPage;
    private AttendancesSummaryService $attendanceSummaryService;
    private AttendanceSummaryDetailService $attendanceSummaryDetailService;

    public function __construct()
    {
        $this->perPage = 10;
        $this->attendanceSummaryService = new AttendancesSummaryService();
        $this->attendanceSummaryDetailService = new AttendanceSummaryDetailService();
    }

    /**
     * @throws AuthorizationException
     */
    public function index(): View
    {
        $this->authorize('view', AttendancesSummary::class);
        return view('pages.adms.attendances-summary.index');
    }

    public function search(Request $request): JsonResponse
    {
        return response()->json($this->attendanceSummaryService->search($request));
    }

    public function filterByDate(AttendancesSummaryFilterByDateRequest $request, User $user): JsonResponse
    {
        return response()->json($this->attendanceSummaryDetailService->filterByDate($request, $user));
    }

    public function detail(Request $request, User $user): View
    {
        return view('pages.adms.attendances-summary.detail', compact('user'));
    }


    public function detailData(Request $request, User $user): JsonResponse
    {
        return response()->json($this->attendanceSummaryDetailService->data($request, $user->absent_id));
    }

    public function data(): JsonResponse
    {
        return response()->json($this->attendanceSummaryService->data());
    }

    public function correction($datePeriod): JsonResponse
    {
        $parseDatePeriod = Carbon::parse($datePeriod)->format('Y-m-d');
        $attendaceVal = AttendancesSummary::whereDate('date', $parseDatePeriod)->first() ?? $parseDatePeriod;
        return response()->json($attendaceVal);
    }

    public function saveCorrection(AttendanceCorrectionRequest $request, User $user, $datePeriod = null): JsonResponse
    {
        $parseDatePeriod = Carbon::parse($datePeriod)->format('Y-m-d');
        $attendaceVal = AttendancesSummary::where('employee_id', $user->absent_id)->whereDate(
            'date',
            $parseDatePeriod
        )->first();

        if ($attendaceVal) {
            $attendaceVal->update([
                'date' => $request->input('date'),
                'clock_in' => $request->input('clock_in'),
                'clock_out' => $request->input('clock_out'),
            ]);
        } else {
            AttendancesSummary::create([
                'date' => $request->input('date'),
                'clock_in' => $request->input('clock_in'),
                'employee_id' => $user->absent_id,
                'clock_out' => $request->input('clock_out'),
            ]);
        }

        return response()->json(['message' => 'Data berhasil disimpan.']);
    }

}
