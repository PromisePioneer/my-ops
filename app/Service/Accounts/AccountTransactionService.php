<?php

namespace App\Service\Accounts;

use App\Models\AccountTransaction;

class AccountTransactionService
{
    public function createDebitTransaction(
        ?int $branchId,
        string $description,
        float|int $amount,
        ?int $accountId = null,
    ): void {
        AccountTransaction::create([
            'branch_id' => $branchId,
            'date' => date('y-m-d'),
            'account_id' => $accountId,
            'description' => $description,
            'type' => 'debit',
            'amount' => $amount,
        ]);
    }

    public function createCreditTransaction(
        ?int $branchId,
        string $description,
        int $amount,
        ?int $accountId = null,
    ): void {
        AccountTransaction::create([
            'branch_id' => $branchId,
            'date' => date('y-m-d'),
            'account_id' => $accountId,
            'description' => $description,
            'type' => 'credit',
            'amount' => $amount,
        ]);
    }
}
