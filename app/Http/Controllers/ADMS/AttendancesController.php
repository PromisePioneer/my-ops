<?php

namespace App\Http\Controllers\ADMS;

use App\Http\Controllers\Controller;
use App\Models\Attendances;
use Illuminate\Http\JsonResponse;

class AttendancesController extends Controller
{

    public readonly int $perPage;

    public function __construct()
    {
        $this->attendance = new Attendances();
        $this->perPage = 10;
    }

    public function index()
    {
        $attendance = Attendances::all();
        return view('pages.adms.attendances.index', compact('attendance'));
    }

    public function data(): JsonResponse
    {
        return response()->json($this->attendance->getAttendanceWithPagination($this->perPage));
    }
}
