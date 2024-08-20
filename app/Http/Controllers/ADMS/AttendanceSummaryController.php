<?php

namespace App\Http\Controllers\ADMS;

use App\Http\Controllers\Controller;
use App\Models\Attendances;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AttendanceSummaryController extends Controller
{

    public readonly int $perPage;
    private Attendances $attendances;

    public function __construct()
    {
        $this->attendances = new Attendances();
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
}
