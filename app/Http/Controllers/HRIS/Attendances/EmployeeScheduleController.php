<?php

namespace App\Http\Controllers\HRIS\Attendances;

use AllowDynamicProperties;
use App\Http\Controllers\Controller;
use App\Models\EmployeeSchedule;
use App\Models\WorkTime;
use Carbon\Carbon;
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

    public function data(Request $request): JsonResponse
    {
        return response()->json($this->employeeScheduleService->data($request));
    }

    public function getWorkTime(Request $request): JsonResponse
    {
        return response()->json($this->workTime->getData($request));
    }

    public function search(Request $request): JsonResponse
    {
        return response()->json($this->employeeScheduleService->search($request));
    }

    public function selectedWorkTime(EmployeeSchedule $employeeSchedule): JsonResponse
    {
        return response()->json($this->workTime->getSelectedData($employeeSchedule->work_time_id));
    }

    public function getSchedules($date, $absentId): JsonResponse
    {
        $employeeSchedules = EmployeeSchedule::whereDate('date', $date)->where('employee_id', $absentId)->first();
        return response()->json($employeeSchedules);
    }

    public function filterByDate(Request $request): JsonResponse
    {
        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);

        return response()->json($this->employeeScheduleService->filterByDate($startDate, $endDate));
    }

    public function saveSchedules(Request $request): JsonResponse
    {
        DB::transaction(function () use ($request) {
            EmployeeSchedule::updateOrCreate([
                'employee_id' => $request->employee_id,
                'date' => $request->date,
            ], [
                'work_time_id' => $request->work_time_id,
                'status' => $request->status,
            ]);
        });
        return response()->json(['message' => 'Data berhasil disimpan.']);
    }
}
