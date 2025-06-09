<?php

namespace App\Support\Journal;

use App\Models\Account;
use App\Models\AccountTransaction;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use function App\Helper\currencyFormat;

class TrialBalanceService
{
    public function data(): Collection
    {
        $accounts = $this->query();
        return self::formattedData($accounts);
    }

    public function query(): Builder
    {
        return Account::with('children')->whereNull('parent_id');
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
                'debit' => $account->trial_balance_type === 'debit' ? currencyFormat(($debit + $childDebit) - $childCredit) : null,
                'credit' => $account->trial_balance_type === 'credit' ? currencyFormat($credit + $childCredit) : null,
                'balance_debit' => $account->trial_balance_type === 'debit' ? floatval(($debit + $childDebit) - ($credit + $childCredit)) : null,
                'balance_credit' => $account->trial_balance_type === 'credit' ? floatval($credit + $childCredit) : null,
            ];
        });
    }

    public function getFilteredTransactionSum($account, $type, ?Request $request): float
    {
        $transactions = $account->accountTransaction()->where('entries_type', $type)->whereBetween('date', [Carbon::now()->subYear()->endOfYear()->firstOfMonth()->format('Y-m-d'), Carbon::now()->endOfYear()->lastOfMonth()->format('Y-m-d')]);

        if ($request?->branch_id) {
            $transactions->where('branch_id', $request->branch_id);
        }

        if ($request?->year) {
            $transactions->whereBetween('date', [
                Carbon::parse($request->year)->subYear()->endOfYear()->firstOfMonth()->format('Y-m-d'),
                Carbon::parse($request->year)->endOfYear()->lastOfMonth()->format('Y-m-d'),
            ]);

        }

        if ($request?->month) {
            $transactions->whereMonth('date', $request->month)->whereYear('date', $request->year);
        }

        return $transactions->sum('amount');
    }

    public function filter(Request $request): array
    {

        $query = $this->query();


        return [
            'trial_balance' => $this->formattedData($query, $request),
            'total_debit' => currencyFormat($this->getTotalDebit($request)->sum('amount')),
            'total_credit' => currencyFormat($this->getTotalCredit($request)->sum('amount')),
        ];
    }

    public function getTotalDebit(Request $request): Builder
    {
        return $this->getFilteredTotal('debit', $request);
    }

    private function getFilteredTotal(string $type, Request $request): Builder
    {
        $query = AccountTransaction::with('account')
            ->whereHas('account', function ($query) use ($type) {
                $query->where('trial_balance_type', $type);
            })->where('entries_type', $type);

        if ($request->branch_id) {
            $query->where('branch_id', $request->branch_id);
        }

        if ($request->year) {
            $query->whereYear('date', $request->year);
        }

        if ($request->month) {
            $query->whereMonth('date', $request->month);
        }

        return $query;
    }

    public function getTotalCredit(Request $request): Builder
    {
        return $this->getFilteredTotal('credit', $request);
    }
}
