<?php

namespace App\Service\Accounts;

use App\Models\AccountTransaction;

class AccountTransactionService
{
    public function createDebitTransaction(
        ?int $branchId,
        string $description,
        ?int $accountId,
        float|int $amount,
    ): void {
        AccountTransaction::create([
            'date' => date('y-m-d'),
            'branch_id' => $branchId ?? null,
            'account_id' => $accountId,
            'description' => $description,
            'transaction_type' => 'TR',
            'entries_type' => 'debit',
            'amount' => $amount,
        ]);
    }

    public function createCreditTransaction(
        ?int $branchId,
        string $description,
        ?int $accountId,
        float|int $amount,
    ): void {
        AccountTransaction::create([
            'branch_id' => $branchId,
            'date' => date('y-m-d'),
            'account_id' => $accountId,
            'description' => $description,
            'transaction_type' => 'TR',
            'entries_type' => 'debit',
            'amount' => $amount,
        ]);
    }
}
