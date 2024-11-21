<?php

namespace App\Http\Controllers\HRIS\Attendances;

use AllowDynamicProperties;
use App\Http\Controllers\Controller;
use App\Http\Requests\EmployeeScheduleRequest;
use App\Models\EmployeeSchedule;
use App\Models\User;
use App\Models\WorkTime;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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
        return response()->json($this->workTime->getSelectedData($employeeSchedule->work_time_id));
    }

    public function getSchedules($date, User $user): JsonResponse
    {
        $employeeSchedules = EmployeeSchedule::whereDate('date', $date)->where('employee_id', $user->absent_id)->first();
        return response()->json($employeeSchedules);
    }

    public function saveSchedules(EmployeeScheduleRequest $request): JsonResponse
    {
        EmployeeSchedule::updateOrCreate([
            'work_time_id' => $request->work_time_id,
            'employee_id' => $request->employee_id,
        ], [
            'date' => $request->date,
            'status' => $request->status
        ]);

        return response()->json(['message' => 'Data berhasil disimpan.']);
    }
}
