<?php

namespace App\Http\Controllers\ManageUser;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\JobInformationRequest;
use App\Models\Department;
use App\Models\User;
use App\Models\UserJobInformation;
use App\Service\JobInformationService;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class JobInformationController extends Controller
{

    private JobInformationService $jobInformationService;
    private Department $department;
    private UserJobInformation $jobInformation;

    public function __construct()
    {
        $this->department = new Department();
        $this->jobInformationService = new JobInformationService();
        $this->jobInformation = new UserJobInformation();
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
        $user = UserJobInformation::where('user_id', $user->id)->first();

        return view('pages.manage-users.user.partials.job-information.view-file', compact('user'));
    }


    public function getSelectedDepartment(User $user): JsonResponse
    {
        $users = $user->whereHas('jobInformation')->first();
        return response()->json($this->department->getSelectedData($users->jobInformation?->department_id));
    }


}
