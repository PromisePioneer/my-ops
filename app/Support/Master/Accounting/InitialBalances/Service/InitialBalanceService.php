<?php

namespace App\Support\Master\Accounting\InitialBalances\Service;

use AllowDynamicProperties;
use App\Models\Account;
use App\Models\AccountTransaction;
use App\Support\Master\Accounting\InitialBalances\Repositories\InitialBalanceRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

#[AllowDynamicProperties] class InitialBalanceService
{
    private static int $perPage = 10;


    public function __construct()
    {
        $this->initialBalanceRepository = new InitialBalanceRepository();
    }

    public function data(Request $request)
    {
        $data = $this->initialBalanceRepository->handle()->paginate(self::$perPage);
        return $this->formattedData($data, $request);
    }


    public function search(Request $request)
    {
        $search = $request->input('search');
        $query = Account::with('accountTransaction', 'children', 'parent')->whereNull('parent_id');
        if (!empty($search)) {
            $query->where('name', 'like', '%' . $search . '%')
                ->orWhere('code', 'like', '%' . $search . '%');
        }

        $data = $query->paginate(self::$perPage);
        return self::formattedData($data);
    }


    public function formattedTotalInitialBalanceData($account, $request = null)
    {
        return $account->get()->map(function ($account) use ($request) {
            if ($account->children->count() > 0) {
                $initialBalanceDebit = $account->children->sum(function ($transaction) use ($request) {
                    return $this->getFilteredTransactionSum($transaction, 'SA', $request, 'debit');
                });

                $initialBalanceCredit = $account->children->sum(function ($transaction) use ($request) {
                    return $this->getFilteredTransactionSum($transaction, 'SA', $request, 'credit');
                });
            } else {
                $initialBalanceDebit = $this->getFilteredTransactionSum($account, 'SA', $request, 'debit');
                $initialBalanceCredit = $this->getFilteredTransactionSum($account, 'SA', $request, 'credit');
            }

            return [
                'id' => $account->id,
                'code' => $account->code,
                'account' => $account->code . ' ' . $account->name,
                'initial_balance_debit' => $account->trial_balance_type === 'debit' ? $initialBalanceDebit : null,
                'initial_balance_credit' => $account->trial_balance_type === 'credit' ? $initialBalanceCredit : null,
            ];
        });

    }

    public function formattedData($account, ?Request $request = null)
    {
        $data = $account->getCollection()->map(function ($account) use ($request) {
            if ($account->children->count() > 0) {
                $initialBalanceDebit = $account->children->sum(function ($transaction) use ($request) {
                    return $this->getFilteredTransactionSum($transaction, 'SA', $request, 'debit');
                });

                $initialBalanceCredit = $account->children->sum(function ($transaction) use ($request) {
                    return $this->getFilteredTransactionSum($transaction, 'SA', $request, 'credit');
                });
            } else {
                $initialBalanceDebit = $this->getFilteredTransactionSum($account, 'SA', $request, 'debit');
                $initialBalanceCredit = $this->getFilteredTransactionSum($account, 'SA', $request, 'credit');
            }

            return [
                'id' => $account->id,
                'code' => $account->code,
                'account' => $account->code . ' ' . $account->name,
                'trial_balance_type' => $account->trial_balance_type,
                'initial_balance_debit' => $account->trial_balance_type === 'debit' ? 'Rp.' . number_format($initialBalanceDebit, 2, '.', '.') : null,
                'initial_balance_credit' => $account->trial_balance_type === 'credit' ? 'Rp.' . number_format($initialBalanceCredit, 2, '.', '.') : null,
                'sub_accounts' => $account->children->map(function ($subAccount) use ($request) {
                    return [
                        'id' => $subAccount->id,
                        'trial_balance_type' => $subAccount->parent->trial_balance_type,
                        'parent_account_code' => $subAccount->parent->code,
                        'sub_account_code' => $subAccount->code,
                        'sub_account_name' => $subAccount->name,
                        'initial_balance_debit' => $subAccount->parent->trial_balance_type === 'debit' ? 'Rp.' . number_format(
                                $this->getFilteredTransactionSum($subAccount, 'SA', $request, 'debit'), 2, '.', '.'
                            )
                            : null,
                        'initial_balance_credit' => $subAccount->parent->trial_balance_type === 'credit' ? 'Rp.' . number_format(
                                $this->getFilteredTransactionSum($subAccount, 'SA', $request, 'credit'), 2, '.', '.'
                            )
                            : null,
                    ];
                }),

            ];
        });


        $account->setCollection($data);
        return $account;
    }


    public function getFilteredTransactionSum($account, $type, ?Request $request, $entriesType = null): float
    {
        $transactions = $account->accountTransaction()
            ->whereYear('date', Carbon::now()->subYear())
            ->where('transaction_type', $type)
            ->where('entries_type', $entriesType);

        if ($request?->branch_id || $request?->user()->branch_id) {
            $transactions->where('branch_id', $request->branch_id ?? $request->user()->branch_id);
        }
        return $transactions->sum('amount');
    }


    public function filter(Request $request)
    {
        $branch = $request->input('branch_id');
        $year = $request->input('year');
        $month = $request->input('month');

        $query = $this->initialBalanceRepository->handle();

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
            'initial_balances' => $this->formattedData($query->paginate(self::$perPage), $request),
            'total_debit' => 'Rp.' . number_format($this->getTotalDebit($request)->sum('amount'), 2, '.', '.'),
            'total_credit' => 'Rp.' . number_format($this->getTotalCredit($request)->sum('amount'), 2, '.', '.'),
        ];
    }


    private function getFilteredTotal(string $type, Request $request): Builder
    {
        $query = AccountTransaction::with('account')
            ->whereHas('account', function ($query) use ($type) {
                $query->where('trial_balance_type', $type);
            })->where('entries_type', $type)
            ->where('transaction_type', 'SA');

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


    public function getTotalDebit(Request $request): Builder
    {
        return $this->getFilteredTotal('debit', $request);
    }

    public function getTotalCredit(Request $request): Builder
    {
        return $this->getFilteredTotal('credit', $request);
    }
}
