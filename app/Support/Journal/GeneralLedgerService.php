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
        return Account::with('children')
            ->where('company_id', $request->session()->get('company_session'))
            ->whereNull('parent_id');
    }

    public function getDetailGeneralLedger(Account $account): Builder|AccountTransaction
    {
        return AccountTransaction::with('account.parent')->whereHas('account.parent', function ($query) {
            $query->where('company_id', session()->get('company_session'));
        })->whereHas('account', function ($query) use ($account) {
            $query->where('parent_id', $account->id)->orWhere('parent_id', null);
        })->whereYear('date', AccountingPeriod::first()->year);
    }


    public function filter(Account $account, Request $request)
    {
        $query = $this->getDetailGeneralLedger($account);


        if ($request->filled('month')) {
            $query->whereMonth('date', $request->month);
        }
        $query->get();

        return self::formattedData($query);
    }

    private static function formattedData($generalLedgerCollection)
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
