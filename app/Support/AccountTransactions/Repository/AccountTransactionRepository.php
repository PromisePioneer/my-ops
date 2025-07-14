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
}
