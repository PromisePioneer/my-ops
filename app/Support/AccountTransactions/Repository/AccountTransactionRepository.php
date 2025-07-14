<?php

namespace App\Support\AccountTransactions\Repository;

use AllowDynamicProperties;
use App\Models\AccountingPeriod;
use App\Models\AccountTransaction;
use Carbon\Carbon;
use Illuminate\Http\Request;

#[AllowDynamicProperties] class AccountTransactionRepository
{
    public function __construct()
    {
        $this->accountTransaction = new AccountTransaction();
    }


    public function findByType(string $type, Request $request)
    {
        return AccountTransaction::with('account.parent')
            ->whereYear('date', AccountingPeriod::first()->year)
            ->whereHas('account.parent', function ($query) use ($type, $request) {
                $query->where('company_id', $request->session()->get('company_session'))
                    ->where('trial_balance_type', $type);
            })->where('transaction_type', 'SA')
            ->where('entries_type', $type);
    }
}
