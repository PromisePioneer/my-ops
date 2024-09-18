<?php

namespace App\Http\Controllers\ADMS;

use App\Http\Controllers\Controller;
use App\Service\Attendances\AttendancesService;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class AttendancesController extends Controller
{
    public readonly int $perPage;

    private AttendancesService $attendanceService;

    public function __construct()
    {
        $this->attendanceService = new AttendancesService();
        $this->perPage = 10;
    }

    public function index(): View
    {
        return view('pages.adms.attendances.index');
    }

    public function data(): JsonResponse
    {
        return response()->json($this->attendanceService->attendancesLog());
    }


}
