<?php

namespace App\Support\User\JobInformation;

use App\Http\Requests\User\JobInformationRequest;
use App\Models\ContractManagement;
use App\Models\JobInformation;
use App\Models\User;
use App\Support\User\ContractManagement\ContractManagementService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class JobInformationService
{


    public function __construct()
    {
        $this->contractManagementService = new ContractManagementService();
    }

    public function update(JobInformationRequest $request, User $user): void
    {
        DB::transaction(function () use ($request, $user) {
            JobInformation::updateOrCreate([
                'user_id' => $user->id,
            ], [
                'fixed_salary' => $request->fixed_salary,
                'position_allowance' => $request->position_allowance,
                'week_holiday' => $request->week_holiday ?? 'Minggu',
                'contract_status' => $request->contract_status,
                'bank_account_number' => $request->bank_account_number,
                'bpjs_kes' => $request->bpjs_kes,
                'no_kpj' => $request->no_kpj,
                'bpjs_ket' => $request->bpjs_ket,
                'no_kis' => $request->no_kis,
            ]);

            if ($request->contract_status == 'Kontrak') {
                ContractManagement::updateOrCreate([
                    'user_id' => $user->id,
                    'contract_number' => $this->contractManagementService
                        ->generateContractNumber($user->join_date, $user),
                ], [
                    'start_date' => $user->join_date,
                    'end_date' => Carbon::parse($user->join_date)->addYear(),
                ]);
            }
        });
    }
}
