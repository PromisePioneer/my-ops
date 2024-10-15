<?php

namespace App\Http\Controllers\HRIS\Attendances;

use App\Http\Controllers\Controller;
use App\Http\Requests\AttendancesSummaryFilterByDateRequest;
use App\Models\AttendancesSummary;
use App\Models\User;
use App\Service\Attendances\AttendancesSummaryService;
use App\Service\Attendances\AttendanceSummaryDetailService;
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

}
