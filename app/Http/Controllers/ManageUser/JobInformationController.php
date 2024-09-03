<?php

namespace App\Http\Controllers\ManageUser;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\JobInformationRequest;
use App\Models\Department;
use App\Models\JobInformation;
use App\Models\User;
use App\Service\JobInformationService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class JobInformationController extends Controller
{
    private JobInformationService $jobInformationService;
    private Department $department;
    private JobInformation $jobInformation;

    public function __construct()
    {
        $this->department = new Department();
        $this->jobInformationService = new JobInformationService();
        $this->jobInformation = new JobInformation();
    }

    public function index(User $user): JsonResponse
    {
        return response()->json($this->jobInformation->getRelatedUserJobInformation($user->id));
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

    public function getSelectedDepartment(User $user): JsonResponse
    {
        $users = $user->whereHas('jobInformation')->first();

        return response()->json($this->department->getSelectedData($users->jobInformation?->department_id));
    }

    public function contractFile(Request $request, User $user): Response
    {
        $contractNumber = $this->jobInformationService->generateContractNumber($user);

        $pdf = Pdf::loadView('pages.manage-users.user.partials.employee-data.job-information.contract-file',
            compact('user')
        )->setPaper(
            'A4',
            'portrait'
        );

        return $pdf->stream();
    }
}
