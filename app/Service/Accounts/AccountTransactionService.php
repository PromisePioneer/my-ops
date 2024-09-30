<?php

namespace App\Service\Accounts;

use App\Models\AccountTransaction;
use Illuminate\Http\Request;

class AccountTransactionService
{
    public function createDebitTransaction(
        Request $request,
        string $description,
        float|int $amount,
        ?int $accountId = null,
    ): void {
        AccountTransaction::create([
            'branch_id' => $request?->branch_id ?? $request->user()->branch_id,
            'date' => date('y-m-d'),
            'account_id' => $accountId,
            'description' => $description,
            'type' => 'debit',
            'amount' => $amount,
        ]);
    }

    public function createCreditTransaction(
        Request $request,
        string $description,
        int $amount,
        ?int $accountId = null,
    ): void {
        AccountTransaction::create([
            'branch_id' => $request?->branch_id ?? $request->user()->branch_id,
            'date' => date('y-m-d'),
            'account_id' => $accountId,
            'description' => $description,
            'type' => 'credit',
            'amount' => $amount,
        ]);
    }
}
