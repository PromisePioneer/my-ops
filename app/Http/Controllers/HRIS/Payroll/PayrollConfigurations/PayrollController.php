<?php

namespace App\Http\Controllers\HRIS\Payroll\PayrollConfigurations;

use AllowDynamicProperties;
use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Service\Attendances\AttendancesSummaryService;
use App\Service\HelperService\FinancialClosePeriodService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

#[AllowDynamicProperties] class PayrollController extends Controller
{
    private Role $role;

    public function __construct()
    {
        $this->role = new Role();
        $this->financialClosePeriodService = new FinancialClosePeriodService();
        $this->attendanceSummaryService = new AttendancesSummaryService();
    }

    public function index(): View
    {
        return view('pages.payroll.index');
    }

    public function getRolesData(Request $request): JsonResponse
    {
        return response()->json($this->role->getData($request));
    }
}
