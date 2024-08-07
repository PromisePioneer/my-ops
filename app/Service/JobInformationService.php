<?php

namespace App\Service;

use App\Http\Requests\User\JobInformationRequest;
use App\Models\User;
use App\Models\UserJobInformation;

class JobInformationService
{
    private HandleFileUploadService $handleUploadService;

    public function __construct()
    {
        $this->handleUploadService = new HandleFileUploadService;
    }

    public function update(JobInformationRequest $request, User $user): void
    {
        $currentJobInfoId = UserJobInformation::where('user_id', $user->id)->first();
        UserJobInformation::updateOrCreate([
            'user_id' => $user->id,
        ], [
            'emp_code' => '123123',
            'absent_id' => '123123',
            'department_id' => $request->department_id,
            'join_date' => $request->join_date,
            'fixed_salary' => $request->fixed_salary,
            'contract_status' => $request->contract_status,
            'bank_account_number' => $request->bank_account_number,
            'bpjs_kes' => $request->bpjs_kes,
            'no_kpj' => $request->bpjs_ket === 'ya' ? $request->no_kpj : null,
            'bpjs_ket' => $request->bpjs_ket,
            'no_kis' => $request->bpjs_kes === 'ya' ? $request->no_kis : null,
            'placement_id' => $request->placement_id,
            'sk_file' => $this->handleUploadService->upload($request, 'documents/sk', 'sk_file', $currentJobInfoId ? $currentJobInfoId->sk_file : null),
            'contract_file' => $this->handleUploadService->upload($request, 'documents/contract-file', 'contract_file', $currentJobInfoId ? $currentJobInfoId->contract_file : null),
        ]);
    }
}
