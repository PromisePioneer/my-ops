<?php

namespace App\Http\Controllers\ADMS;

use App\Http\Controllers\Controller;
use App\Models\Attendances;

class AttendancesController extends Controller
{
    public function index()
    {
        $attendance = Attendances::all();
        return view('pages.adms.attendances.index', compact('attendance'));
    }
}
