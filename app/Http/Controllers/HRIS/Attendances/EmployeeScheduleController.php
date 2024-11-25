<?php

namespace App\Http\Controllers\HRIS\Attendances;

use AllowDynamicProperties;
use App\Http\Controllers\Controller;
use App\Models\EmployeeSchedule;
use App\Models\WorkTime;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

#[AllowDynamicProperties] class EmployeeScheduleController extends Controller
{
    public function __construct()
    {
        $this->employeeScheduleService = new EmployeeScheduleService();
        $this->workTime = new WorkTime();
    }

    public function index(): View
    {
        return view('pages.adms.employee-schedules.index');
    }

    public function data(): JsonResponse
    {
        return response()->json($this->employeeScheduleService->data());
    }


    public function getWorkTime(Request $request): JsonResponse
    {
        return response()->json($this->workTime->getData($request));
    }

    public function selectedWorkTime(EmployeeSchedule $employeeSchedule): JsonResponse
    {
        dd($employeeSchedule->id);
        return response()->json($this->workTime->getSelectedData($employeeSchedule->work_time_id));
    }

    public function getSchedules($date, $absentId): JsonResponse
    {
        $employeeSchedules = EmployeeSchedule::whereDate('date', $date)->where('employee_id', $absentId)->first();
        return response()->json($employeeSchedules);
    }

    public function saveSchedules(Request $request): JsonResponse
    {
        DB::transaction(function () use ($request) {
            EmployeeSchedule::updateOrCreate([
                'employee_id' => $request->employee_id,
                'work_time_id' => $request->work_time_id,
            ], [
                'status' => $request->status,
                'date' => $request->date,
            ]);
        });
        return response()->json(['message' => 'Data berhasil disimpan.']);
    }
}
