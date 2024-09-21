<?php

namespace App\Service\Accounts;

use App\Models\AccountTransaction;

class AccountTransactionService
{
    public function createDebitTransaction(
        string $description,
        float|int $amount,
        ?int $accountId = null,
        ?int $subAccountId = null
    ): void {
        AccountTransaction::create([
            'date' => date('y-m-d'),
            'account_id' => $accountId,
            'sub_account_id' => $subAccountId,
            'description' => $description,
            'debit' => $amount,
            'credit' => 0,
        ]);
    }

    public function createCreditTransaction(
        string $description,
        int $amount,
        ?int $accountId = null,
        ?int $subAccountId = null
    ): void {
        AccountTransaction::create([
            'date' => date('y-m-d'),
            'account_id' => $accountId,
            'sub_account_id' => $subAccountId,
            'description' => $description,
            'debit' => 0,
            'credit' => $amount,
        ]);
    }
}
