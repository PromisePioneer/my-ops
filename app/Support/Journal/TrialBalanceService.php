<?php

namespace App\Support\Journal;

use App\Models\Account;
use App\Models\AccountTransaction;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class TrialBalanceService
{
    public function data(): Collection
    {
        $accounts = $this->query();
        return self::formattedData($accounts);
    }

    public function query(): Builder
    {
        return Account::with('children', 'accountTransaction')->whereNull('parent_id');
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
                'account_name' => $account->name,
                'debit' => $account->trial_balance_type === 'debit' ? 'Rp.' . number_format(($debit + $childDebit) - $childCredit, 2) : null,
                'credit' => $account->trial_balance_type === 'credit' ? 'Rp.' . number_format($credit + $childCredit, 2) : null,
                'balance' => 'Rp.' . number_format(($debit + $childDebit) - ($credit + $childCredit), 2),
            ];
        });
    }

    public function getFilteredTransactionSum($account, $type, ?Request $request): float
    {
        $transactions = $account->accountTransaction()->where('entries_type', $type);

        if ($request?->branch_id) {
            $transactions->where('branch_id', $request->branch_id);
        }

        if ($request?->year) {
            $transactions->whereBetween('date', [
                Carbon::parse($request->year)->subYear()->endOfYear()->firstOfMonth()->format('Y-m-d'),
                $request->year
            ]);
        }

        if ($request?->month) {
            $transactions->whereMonth('date', $request->month);
        }

        return $transactions->sum('amount');
    }

    public function filter(Request $request): array
    {
        $branch = $request->input('branch_id');
        $year = $request->input('year');
        $month = $request->input('month');

        $query = $this->query();

        if ($branch) {
            $query->orWhereHas('accountTransaction', function (Builder $query) use ($branch) {
                $query->where('branch_id', $branch ?? null);
            });
        }

        if ($year) {
            $query->orWhereHas('accountTransaction', function (Builder $query) use ($year) {
                $query->whereYear('date', $year);
            });
        }

        if ($month) {
            $query->orWhereHas('accountTransaction', function (Builder $query) use ($month) {
                $query->whereMonth('date', $month);
            });
        }

        if ($year && $month) {
            $query->orWhereHas('accountTransaction', function (Builder $query) use ($year, $month) {
                $query->whereYear('date', $year)
                    ->whereMonth('date', $month);
            });
        }

        return [
            'trial_balance' => $this->formattedData($query, $request),
            'total_debit' => number_format($this->getTotalDebit($request)->sum('amount'), 2),
            'total_credit' => number_format($this->getTotalCredit($request)->sum('amount'), 2),
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
                $query->where('trial_balance_type', 'debit');
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
