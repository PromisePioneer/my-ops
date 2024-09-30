<?php

namespace App\Service\Journal;

use App\Models\Account;
use App\Models\AccountTransaction;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Query\Builder;

class GeneralLedgerService
{
    public function getAccountData(): Collection
    {
        return Account::all();
    }

    public function getDetailGeneralLedger(Account $account): \Illuminate\Database\Eloquent\Builder
    {
        $isAccountHasParent = Account::where('parent_id', $account->id)->exists();


        $data = Account::join(
            'account_transactions',
            'accounts.id',
            '=',
            'account_transactions.account_id'
        );

        if ($isAccountHasParent) {
            $data->where('accounts.parent_id', $account->id);
        } else {
            $data->where('accounts.id', $account->id);
        }

        return $data;
    }


    public function filterByPeriod(Account $account, $month, $year)
    {
        $generalLedger = $this->getAccountTransaction($account)
            ->whereMonth('date', $month)
            ->whereYear('date', $year)->get()->groupBy('description');
        return $this->formattedData($generalLedger);
    }

    public function getAccountTransaction(Account $account): Builder
    {
        return AccountTransaction::with('account', 'subAccount')
            ->whereHas('account', function ($query) use ($account) {
                $query->where('id', $account->id);
            })->orWhereHas('subAccount', function ($query) use ($account) {
                $query->where('account_id', $account->id);
            });
    }

    public function formattedData($generalLedgerCollection)
    {
        return $generalLedgerCollection->map(function ($item) {
            return [
                'date' => $item->first()->created_at->format('d/m/Y'),
                'description' => $item->first()->description,
                'debit' => $item->where('debit', '>', 0)->map(function ($transaction) {
                    return [
                        'amount' => 'Rp.'.number_format($transaction->debit),
                    ];
                })->values(),
                'credit' => $item->where('credit', '>', 0)->map(function ($transaction) {
                    return [
                        'amount' => 'Rp.'.number_format($transaction->credit),
                    ];
                })->values(),
            ];
        })->filter()->values();
    }

}