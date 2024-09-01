<?php

namespace App\Service;

use App\Http\Requests\User\JobInformationRequest;
use App\Models\JobInformation;
use App\Models\User;

class JobInformationService
{


    public function update(JobInformationRequest $request, User $user): void
    {
        JobInformation::updateOrCreate([
            'user_id' => $user->id,
        ], [
            'department_id' => $request->department_id,
            'fixed_salary' => $request->fixed_salary,
            'contract_status' => $request->contract_status,
            'contract_end_date' => $request->contract_end_date,
            'bank_account_number' => $request->bank_account_number,
            'bpjs_kes' => $request->bpjs_kes,
            'no_kpj' => $request->bpjs_ket === 'ya' ? $request->no_kpj : null,
            'bpjs_ket' => $request->bpjs_ket,
            'no_kis' => $request->bpjs_kes === 'ya' ? $request->no_kis : null,
        ]);
    }
}
