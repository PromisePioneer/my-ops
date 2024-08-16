<?php

namespace App\Http\Controllers\ADMS;

use App\Http\Controllers\Controller;
use App\Models\Attendances;
use App\Models\User;
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
        $att = User::leftJoin('attendances', 'attendances.employee_id', '=', 'users.absent_id')
            ->select('users.*', 'attendances.*')
            ->get()
            ->groupBy('name')->map(function ($item) {
                return [
                    'name' => $item->first()->name,
                    'absent_id' => $item->first()->absent_id,
                    'timestamp' => $item->first()->timestamp,
                    'check_in_time' => $item->where('status1', 0)->first()->timestamp ?? null,
                    'check_out_time' => $item->where('status1', 1)->first()->timestamp ?? null,
                    'check_in' => $item->first()->status1 === 0 ? 0 : null,
                    'check_out' => $item->first()->status1 === 1 ? 1 : null,
                ];
            });


        return response()->json($att);
    }

    public function attendancesSummary()
    {
        $att = User::leftJoin('attendances', 'attendances.employee_id', '=', 'users.absent_id')
            ->select('users.*', 'attendances.*')
            ->groupBy('name')
            ->get()->map(function ($item) {
                return [
                    'name' => $item->first()->name,
                    'timestamp' => $item->first()->timestamp,
                ];
            });
    }
}
