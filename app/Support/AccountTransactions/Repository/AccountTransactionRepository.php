<?php

namespace App\Support\AccountTransactions\Repository;

use AllowDynamicProperties;
use App\Models\AccountTransaction;
use Carbon\Carbon;

#[AllowDynamicProperties] class AccountTransactionRepository
{
    public function __construct()
    {
        $this->accountTransaction = new AccountTransaction();
    }


    public function findByType(string $type)
    {
        return AccountTransaction::with('account')
            ->whereHas('account', function ($query) use ($type) {
                $query->where('trial_balance_type', $type);
            })->where('entries_type', $type)
            ->whereYear('date', Carbon::now()->subYear())
            ->where('transaction_type', 'SA');
    }
}
