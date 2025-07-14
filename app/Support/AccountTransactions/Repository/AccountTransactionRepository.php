<?php

namespace App\Support\AccountTransactions\Repository;

use AllowDynamicProperties;
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
        return AccountTransaction::with('account')
            ->whereHas('account.parent', function ($query) use ($request, $type) {
                $query->where('trial_balance_type', $type)
                    ->where('company_id', $request->session()->get('company_session'));
            })->where('entries_type', $type)
            ->where('transaction_type', 'SA');
    }
}
