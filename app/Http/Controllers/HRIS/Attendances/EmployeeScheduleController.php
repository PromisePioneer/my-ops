<?php

namespace App\Http\Controllers\HRIS\Attendances;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class EmployeeScheduleController extends Controller
{

    private EmployeeScheduleService $employeeScheduleService;

    public function __construct()
    {
        $this->employeeScheduleService = new EmployeeScheduleService();
    }

    public function index(): View
    {
        return view('pages.adms.employee-schedules.index');
    }

    public function data(): JsonResponse
    {
        return response()->json($this->employeeScheduleService->data());
    }
}
