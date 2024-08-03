<?php

namespace App\Http\Controllers\ManageUser;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\PayrollRequest;
use App\Models\CompanyProfile;
use App\Models\Payroll;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class PayrollController extends Controller
{
    private static int $perPage = 10;
    protected Payroll $payroll;

    public function __construct()
    {
        $this->payroll = new Payroll();
    }
    public function index(): View
    {
        return view('pages.manage-users.payroll.index');
    }

    public function data(): JsonResponse
    {
        $payroll = Payroll::with('user', 'user.jobInformation')->paginate(self::$perPage);
        return response()->json($payroll);
    }

    public function search(Request $request): JsonResponse
    {
        return response()->json($this->payroll->searchPayroll($request));
    }

    public function getUserData(Request $request): JsonResponse
    {
        $search = $request->search;
        if ($search === '') {
            $user = User::with('jobInformation')->orderby('name', 'asc')
                ->select('id', 'name')
                ->get();
        } else {
            $user = User::with('jobInformation')
                ->orderby('name', 'asc')
                ->select('id', 'name')
                ->where('name', 'like', '%' . $search . '%')
                ->get();
        }
        $response = array();
        foreach ($user as $c) {
            $response[] = array(
                "id" => $c->id,
                "text" => $c->name
            );
        }
        return response()->json($response);
    }

    public function create()
    {
        return view('pages.manage-users.payroll.create');
    }

    public function store(PayrollRequest $request): JsonResponse
    {
        $payroll = Payroll::create($request->validated());
        return response()->json($payroll);
    }

    public function edit()
    {
        return view('pages.manage-users.payroll.edit');
    }

    public function update(PayrollRequest $request, Payroll $payroll): JsonResponse
    {
        return response()->json($request->validated());
    }

    public function destroy(Payroll $payroll): JsonResponse
    {
        return response()->json($payroll->delete());
    }


    public function exportToPDF(Payroll $payroll): Response
    {
        $slipSalary = $payroll->with('user', 'user.branch', 'user.jobInformation')->first();
        $allowanceAndBonusSum = $slipSalary->positional_allowance + $slipSalary->meal_allowance + $slipSalary->transportation_allowance + $slipSalary->overtime_allowance + $slipSalary->sales_bonus + $slipSalary->project_bonus + $slipSalary->other_bonus;

        $dues = $slipSalary->bpjs_kes_dues + $slipSalary->bpjs_tek_dues;

        $netSalaryReceived = $slipSalary->user->jobInformation->fixed_salary - $dues - $allowanceAndBonusSum;


        $companyProfile = CompanyProfile::where('id', 1)->first();
        $pdf = Pdf::loadView('pages.manage-users.payroll.export-pdf', compact('slipSalary', 'companyProfile', 'allowanceAndBonusSum', 'dues', 'netSalaryReceived'))->setPaper("A4", 'portrait');

        return $pdf->stream();
    }
}
