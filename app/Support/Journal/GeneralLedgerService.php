<?php

namespace App\Support\Journal;

use App\Models\Account;
use App\Models\AccountingPeriod;
use App\Models\AccountTransaction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class GeneralLedgerService
{
    public function getAccountData(Request $request)
    {
        return Account::where('company_id', $request->session()->get('company_id'))->get();
    }

    public function getDetailGeneralLedger(Account $account): Builder|AccountTransaction
    {
        $isAccountHasParent = Account::where('parent_id', $account->id)->exists();


        $data = Account::join(
            'account_transactions',
            'accounts.id',
            '=',
            'account_transactions.account_id'
        )->whereYear('account_transactions.date', AccountingPeriod::first()->year);

        if ($isAccountHasParent) {
            $data->where('accounts.parent_id', $account->id);
        } else {
            $data->where('accounts.id', $account->id);
        }

        return $data;
    }


    public function filterByPeriod(Account $account, $month, $year)
    {
        $generalLedger = $this->getDetailGeneralLedger($account)
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->get();

        return self::formattedData($generalLedger);
    }

    public function formattedData($generalLedgerCollection)
    {
        return $generalLedgerCollection->map(function ($item) {
            return [
                'date' => $item->first()->created_at->format('d/m/Y'),
                'description' => $item->first()->description,
                'debit' => $item->where('entries_type', 'debit')->map(function ($transaction) {
                    return [
                        'amount' => 'Rp.' . number_format($transaction->debit),
                    ];
                })->values(),
                'credit' => $item->where('entries_type', 'credit')->map(function ($transaction) {
                    return [
                        'amount' => 'Rp.' . number_format($transaction->credit),
                    ];
                })->values(),
            ];
        })->values();
    }

}
