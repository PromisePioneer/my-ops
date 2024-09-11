<?php

namespace App\Observers;

use App\Models\Account;
use App\Models\AccountTransaction;
use App\Models\SubAccount;

class AccountTransactionObserver
{
    /**
     * Handle the AccountTransaction "created" event.
     *
     * @return void
     */
    public function created(AccountTransaction $accountTransaction): void
    {
        if ($accountTransaction->account_id) {
            $account = Account::where('id', $accountTransaction->account_id)->first();

            $account->debit_balance += $accountTransaction->debit;
            $account->credit_balance += $accountTransaction->credit;
            $account->balance = $account->debit_balance - $account->credit_balance;
            $account->save();
        }

        if ($accountTransaction->sub_account_id) {
            $subAccount = SubAccount::where('id', $accountTransaction->sub_account_id)->first();

            $subAccount->debit_balance += $accountTransaction->debit;
            $subAccount->credit_balance += $accountTransaction->credit;
            $subAccount->balance = $subAccount->debit_balance - $subAccount->credit_balance;
            $subAccount->save();

            $parentAccount = $subAccount->account;

            $parentAccount->debit_balance = $subAccount->sum('debit_balance');
            $parentAccount->credit_balance = $subAccount->sum('credit_balance');
            $parentAccount->balance = $parentAccount->debit_balance - $parentAccount->credit_balance;
            $parentAccount->save();
        }
    }

    /**
     * Handle the AccountTransaction "updated" event.
     *
     * @return void
     */
    public function updated(AccountTransaction $accountTransaction)
    {
        //
    }

    /**
     * Handle the AccountTransaction "deleted" event.
     *
     * @return void
     */
    public function deleted(AccountTransaction $accountTransaction)
    {
        //
    }

    /**
     * Handle the AccountTransaction "restored" event.
     *
     * @return void
     */
    public function restored(AccountTransaction $accountTransaction)
    {
        //
    }

    /**
     * Handle the AccountTransaction "force deleted" event.
     *
     * @return void
     */
    public function forceDeleted(AccountTransaction $accountTransaction)
    {
        //
    }
}
