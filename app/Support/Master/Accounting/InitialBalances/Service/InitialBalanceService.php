<?php

namespace App\Support\Master\Accounting\InitialBalances\Service;

use AllowDynamicProperties;
use App\Models\Account;
use App\Support\Master\Accounting\InitialBalances\Repositories\InitialBalanceRepository;
use Carbon\Carbon;
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
        $query = Account::with('accountTransaction', 'children')->whereNull('parent_id');
        if (!empty($search)) {
            $query->where('name', 'like', '%' . $search . '%')
                ->orWhere('code', 'like', '%' . $search . '%');
        }

        $data = $query->paginate(self::$perPage);
        return self::formattedData($data);
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
                'initial_balance_debit' => 'Rp.' . number_format($initialBalanceDebit, 2, '.', '.') ?? null,
                'initial_balance_credit' => 'Rp.' . number_format($initialBalanceCredit, 2, '.', '.') ?? null,
                'sub_accounts' => $account->children->map(function ($subAccount) use ($request) {
                    return [
                        'id' => $subAccount->id,
                        'parent_account_code' => $subAccount->parent->code,
                        'sub_account_code' => $subAccount->code,
                        'sub_account_name' => $subAccount->name,
                        'initial_balance_debit' => 'Rp.' . number_format(
                                $this->getFilteredTransactionSum($subAccount, 'SA', $request, 'debit'), 2, '.', '.'
                            )
                            ?? null,
                        'initial_balance_credit' => 'Rp.' . number_format(
                                $this->getFilteredTransactionSum($subAccount, 'SA', $request, 'credit'), 2, '.', '.'
                            )
                            ?? null,
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
        $data = $this->initialBalanceRepository->handle()->paginate(self::$perPage);
        return self::formattedData($data, $request);
    }
}
