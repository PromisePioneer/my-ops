<?php

namespace App\Support\Journal;

use App\Models\Account;
use App\Models\AccountingPeriod;
use App\Models\AccountTransaction;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use function App\Helper\currencyFormat;

class TrialBalanceService
{
    public function data(Request $request): Collection
    {
        $accounts = $this->query($request);
        return self::formattedData($accounts);
    }

    public function query(Request $request): Builder
    {
        return Account::with('children')
            ->where('company_id', $request->session()->get('company_session'))
            ->whereNull('parent_id');
    }

    public function formattedData(Builder $accounts, ?Request $request = null): Collection
    {
        return $accounts->get()->map(function ($account) use ($request) {
            $debit = 0;
            $childDebit = 0;

            if ($account->trial_balance_type === 'debit') {
                $debit = $this->getFilteredTransactionSum($account, 'debit', $request);
                $childDebit = $account->children->sum(function ($child) use ($request, $account) {
                    return $this->getFilteredTransactionSum($child, 'debit', $request);
                });
            }

            $credit = $this->getFilteredTransactionSum($account, 'credit', $request);

            $childCredit = $account->children->sum(function ($child) use ($request) {
                return $this->getFilteredTransactionSum($child, 'credit', $request);
            });


            return [
                'trial_balance_type' => $account->trial_balance_type,
                'account_name' => $account->name,
                'debit' => $account->trial_balance_type === 'debit'
                    ? currencyFormat($debit + $childDebit)
                    : null,
                'credit' => $account->trial_balance_type === 'credit'
                    ? currencyFormat($credit + $childCredit)
                    : null,
                'balance_debit' => $account->trial_balance_type === 'debit'
                    ? floatval($debit + $childDebit)
                    : null,
                'balance_credit' => $account->trial_balance_type === 'credit'
                    ? floatval($credit + $childCredit)
                    : null,
            ];
        });
    }

    public function getFilteredTransactionSum($account, $type, ?Request $request): float
    {
        $transactions = $account->accountTransaction()->where('entries_type', $type)
            ->whereYear('date', AccountingPeriod::first()->year);

        if ($request?->month) {
            $transactions->whereMonth('date', $request->month);
        }

        if ($request?->branch_id) {
            $transactions->where('branch_id', $request->branch_id);
        }

        return $transactions->sum('amount');
    }

    public function filter(Request $request): array
    {
        $query = $this->query($request);
        return [
            'trial_balance' => $this->formattedData($query, $request),
            'total_debit' => currencyFormat($this->getTotalDebit($request)),
            'total_credit' => currencyFormat($this->getTotalCredit($request)),
        ];
    }

    public function getTotalDebit(Request $request): string
    {
        return $this->getFilteredTotal('debit', $request);
    }

    private function getFilteredTotal(string $type, Request $request)
    {
        $query = AccountTransaction::with('account')
            ->whereHas('account', function ($query) use ($type, $request) {
                $query->where('company_id', $request->session()->get('company_session'))->where('trial_balance_type', $type);
            })->where('entries_type', $type)
            ->whereYear('date', AccountingPeriod::first()->year);

        if ($request->branch_id) {
            $query->where('branch_id', $request->branch_id);
        }

        if ($request->month) {
            $query->whereMonth('date', $request->month);
        }

        return $query->sum('amount');
    }

    public function getTotalCredit(Request $request): string
    {
        return $this->getFilteredTotal('credit', $request);
    }
}
