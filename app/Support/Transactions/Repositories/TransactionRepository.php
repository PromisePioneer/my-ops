<?php

namespace App\Support\Transactions\Repositories;

use App\Models\Transaction;
use Illuminate\Database\Eloquent\Builder;

class TransactionRepository
{
    public function getTransactions(): Builder
    {
        return Transaction::with('branch', 'unitType', 'debitAccount', 'creditAccount', 'item');
    }
}
