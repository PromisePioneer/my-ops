<?php

namespace App\Http\Controllers\ADMS;

use App\Http\Controllers\Controller;
use App\Models\Attendances;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class AttendancesController extends Controller
{

    public readonly int $perPage;
    private Attendances $attendance;

    public function __construct()
    {
        $this->attendance = new Attendances();
        $this->perPage = 10;
    }

    public function index(): View
    {
        return view('pages.adms.attendances.index');
    }

    public function data(): JsonResponse
    {
        return response()->json($this->attendance->getAttendanceWithPagination($this->perPage));
    }

}
