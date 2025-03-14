<?php

namespace App\Http\Controllers\HRIS\EmployeesData;

use AllowDynamicProperties;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\JobInformationRequest;
use App\Models\Department;
use App\Models\JobInformation;
use App\Models\User;
use App\Support\User\JobInformation\JobInformationService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

#[AllowDynamicProperties] class JobInformationController extends Controller
{
    public function __construct()
    {
        $this->department = new Department();
        $this->jobInformationService = new JobInformationService();
        $this->jobInformation = new JobInformation();
    }

    public function index(User $user): JsonResponse
    {
        $jobInformation = JobInformation::where('user_id', $user->id)->first();

        return response()->json([
            'fixed_salary' => "Rp." . number_format($jobInformation->fixed_salary),
            'position_allowance' => $jobInformation->position_allowance,
            'week_holiday' => $jobInformation->week_holiday,
            'contract_status' => $jobInformation->contract_status,
            'bank_account_number' => $jobInformation->bank_account_number,
            'bpjs_kes' => $jobInformation->bpjs_kes,
            'no_kpj' => $jobInformation->no_kpj,
            'bpjs_ket' => $jobInformation->bpjs_ket,
            'no_kis' => $jobInformation->no_kis,
        ]);
    }

    public function update(JobInformationRequest $request, User $user): JsonResponse
    {
        $this->jobInformationService->update($request, $user);

        return response()->json([
            'message' => 'Data berhasil disimpan',
        ]);
    }

    public function viewFile(User $user): View
    {
        $user = JobInformation::where('user_id', $user->id)->first();

        return view('pages.manage-users.user.partials.job-information.view-file', compact('user'));
    }


    public function contractFile(Request $request, User $user): Response
    {
        $contractNumber = $this->jobInformationService->generateContractNumber($user);

        $pdf = Pdf::loadView(
            'pages.manage-users.user.partials.employee-data.job-information.contract-file',
            compact('user')
        )->setPaper(
            'A4',
            'portrait'
        );

        return $pdf->stream();
    }
}
