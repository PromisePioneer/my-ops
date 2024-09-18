<?php

namespace App\Http\Controllers\ADMS;

use App\Http\Controllers\Controller;
use App\Http\Requests\AttendancesSummaryFilterByDateRequest;
use App\Models\User;
use App\Service\Attendances\AttendancesSummaryService;
use App\Service\Attendances\AttendanceSummaryDetailService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

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

    public function index(): View
    {
        return view('pages.adms.attendances-summary.index');
    }

    public function search(Request $request): JsonResponse
    {
        return response()->json($this->attendanceSummaryService->search($request));
    }

    public function filterByDate(AttendancesSummaryFilterByDateRequest $request): JsonResponse
    {
        return response()->json($this->attendanceSummaryService->filterByDate($request));
    }

    public function detail(Request $request, User $user): JsonResponse
    {
        return response()->json($this->attendanceSummaryDetailService->data($request, $user->absent_id));
    }

    public function data(): JsonResponse
    {
        return response()->json($this->attendanceSummaryService->data());
    }

}
