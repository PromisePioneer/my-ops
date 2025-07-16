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
        $transactions = $account->accountTransaction()->where('entries_type', $type);

        if ($request?->year && $request?->month) {
            $transactions->whereMonth('date', $request->month)
                ->whereYear('date', $request->year);
        } elseif ($request?->year) {
            $start = Carbon::createFromDate($request->year - 1, 12, 1)->startOfDay();
            $end = Carbon::createFromDate($request->year, 12, 31)->endOfDay();
            $transactions->whereBetween('date', [$start, $end]);
        } else {
            $start = Carbon::now()->subYear()->startOfMonth()->setMonth(12); // 1 Dec tahun lalu
            $end = Carbon::now()->endOfYear(); // 31 Dec tahun ini
            $transactions->whereBetween('date', [$start, $end]);
        }

        if ($request?->branch_id) {
            $transactions->where('branch_id', $request->branch_id);
        }

        return $transactions->sum('amount');
    }

    public function filter(Request $request): array
    {
        $query = $this->query();
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

    private function getFilteredTotal(string $type, Request $request): string
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

        return $query->sum('amount');
    }

    public function getTotalCredit(Request $request): string
    {
        return $this->getFilteredTotal('credit', $request);
    }
}
