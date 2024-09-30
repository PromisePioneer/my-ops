<?php

namespace App\Service\Journal;

use App\Models\Account;
use App\Models\AccountTransaction;

class FinancialReportService
{
    public function getFixedAssets()
    {
        return Account::with('accountTransaction')
            ->whereBetween('code', ['121', '126'])
            ->get()
            ->map(function ($query) {
                return [
                    'name' => $query->name,
                    'amount' => 'Rp.'.number_format(
                            $query->accountTransaction->where('type', 'debit')->sum('amount'),
                            2
                        ),
                ];
            });
    }


    public function accumulatedDepreciationOfFixedAssetsAccount()
    {
        return AccountTransaction::with('code', '')->first();
    }


}