<?php

namespace App\Service\Journal;

use App\Models\Account;
use App\Models\AccountTransaction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class GeneralLedgerService
{


    private AccountTransaction $accountTransaction;

    public function __construct()
    {
        $this->accountTransaction = new AccountTransaction();
    }

    public function getAccountData(): Collection
    {
        return Account::all();
    }

    public function getDetailGeneralLedger(Account $account)
    {
        $data = $this->getAccountTransaction($account)->get()->groupBy('description');
        return $this->formattedData($data);
    }

    public function getAccountTransaction(Account $account): Builder
    {
        return AccountTransaction::with('account', 'account.subAccount')
            ->whereHas('account', function ($query) use ($account) {
                $query->where('id', $account->id);
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
                        'amount' => 'Rp.'.number_format($transaction->debit) ?? '-',
                    ];
                })->values(),
                'credit' => $item->where('credit', '>', 0)->map(function ($transaction) {
                    return [
                        'amount' => 'Rp.'.number_format($transaction->credit) ?? '-',
                    ];
                })->values(),
            ];
        })->filter()->values();
    }


    public function filterByPeriod(Account $account, $month, $year)
    {
        $generalLedger = $this->getAccountTransaction($account)
            ->whereMonth('date', $month)
            ->whereYear('date', $year)->get()->groupBy('description');
        return $this->formattedData($generalLedger);
    }

}