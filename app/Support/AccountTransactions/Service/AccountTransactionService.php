<?php

namespace App\Support\AccountTransactions\Service;

use AllowDynamicProperties;
use App\Models\AccountTransaction;
use Carbon\Carbon;

#[AllowDynamicProperties] class AccountTransactionService
{

    public function __construct()
    {
        $this->accountTransaction = new AccountTransaction();
    }


    public function createDebitTransaction(
        ?int      $branchId,
        string    $description,
        ?int      $accountId,
        float|int $amount,
                  $transactionId = null
    ): void
    {
        $this->accountTransaction->create([
            'date' => date('y-m-d'),
            'branch_id' => $branchId ?? null,
            'account_id' => $accountId,
            'description' => $description,
            'transaction_type' => 'TR',
            'entries_type' => 'debit',
            'amount' => $amount,
            'transaction_id' => $transactionId
        ]);
    }

    public function createCreditTransaction(
        ?int      $branchId,
        string    $description,
        ?int      $accountId,
        float|int $amount,
                  $transactionId = null,
                  $date = null
    ): void
    {
        $this->accountTransaction->create([
            'branch_id' => $branchId,
            'date' => Carbon::parse($date ?? date('Y-m-d'))->format('Y-m-d'),
            'account_id' => $accountId,
            'description' => $description,
            'transaction_type' => 'TR',
            'entries_type' => 'credit',
            'amount' => $amount,
            'transaction_id' => $transactionId
        ]);
    }

    public function getHistory(int $accountId)
    {
        return AccountTransaction::where('account_id', $accountId)->get();
    }
}
