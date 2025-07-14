<?php

namespace App\Support\Master\Accounting\InitialBalances\Service;

use AllowDynamicProperties;
use App\Models\Account;
use App\Models\AccountingPeriod;
use App\Models\AccountTransaction;
use App\Support\AccountTransactions\Repository\AccountTransactionRepository;
use App\Support\Master\Accounting\InitialBalances\Repositories\InitialBalanceRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use function App\Helper\currencyFormat;

#[AllowDynamicProperties] class InitialBalanceService
{
    private static int $perPage = 10;


    public function __construct()
    {
        $this->initialBalanceRepository = new InitialBalanceRepository();
        $this->accountTransactionRepository = new AccountTransactionRepository();
        $this->accountingPeriod = new AccountingPeriod();
    }

    public function data(Request $request)
    {
        $data = $this->initialBalanceRepository->handle($request)->paginate(self::$perPage);
        return $this->formattedData($data, $request);
    }


    public function search(Request $request)
    {
        $search = $request->input('search');
        $query = $this->initialBalanceRepository->handle($request);
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
            $initialBalanceDebit = $this->sumAccountTransactions($account, $request)['initial_balance_debit'];
            $initialBalanceCredit = $this->sumAccountTransactions($account, $request)['initial_balance_credit'];

            return [
                'id' => $account->id,
                'code' => $account->code,
                'account' => $account->code . ' ' . $account->name,
                'initial_balance_debit' => $account->trial_balance_type === 'debit' ? $initialBalanceDebit : null,
                'initial_balance_credit' => $account->trial_balance_type === 'credit' ? $initialBalanceCredit : null,
            ];
        });

    }


    public function sumAccountTransactions(Account $account, ?Request $request): array
    {
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
            'initial_balance_debit' => $initialBalanceDebit,
            'initial_balance_credit' => $initialBalanceCredit
        ];
    }

    public function formattedData($account, ?Request $request = null)
    {
        $data = $account->getCollection()->map(function ($account) use ($request) {
            $initialBalanceDebit = $this->sumAccountTransactions($account, $request)['initial_balance_debit'];
            $initialBalanceCredit = $this->sumAccountTransactions($account, $request)['initial_balance_credit'];

            return [
                'id' => $account->id,
                'code' => $account->code,
                'account' => $account->code . ' ' . $account->name,
                'trial_balance_type' => $account->trial_balance_type,
                'initial_balance_debit' => $account->trial_balance_type === 'debit' ? currencyFormat($initialBalanceDebit) : null,
                'initial_balance_credit' => $account->trial_balance_type === 'credit' ? currencyFormat($initialBalanceCredit) : null,
                'sub_accounts' => $account->children->map(function ($subAccount) use ($request) {
                    return [
                        'id' => $subAccount->id,
                        'trial_balance_type' => $subAccount->parent->trial_balance_type,
                        'parent_account_code' => $subAccount->parent->code,
                        'sub_account_code' => $subAccount->code,
                        'sub_account_name' => $subAccount->name,
                        'initial_balance_debit' => $subAccount->parent->trial_balance_type === 'debit'
                            ? currencyFormat($this->getFilteredTransactionSum($subAccount, 'SA', $request, 'debit'))
                            : null,
                        'initial_balance_credit' => $subAccount->parent->trial_balance_type === 'credit'
                            ? currencyFormat($this->getFilteredTransactionSum($subAccount, 'SA', $request, 'credit'))
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
            ->where('transaction_type', $type)
            ->where('entries_type', $entriesType)
            ->whereYear('date', AccountingPeriod::first()->year);


        if ($request?->branch_id || $request?->user()->branch_id) {
            $transactions->where('branch_id', $request->branch_id ?? $request->user()->branch_id);
        }


        return $transactions->sum('amount');
    }


    public function filter(Request $request): array
    {
        $query = $this->initialBalanceRepository->handle($request);

        return [
            'initial_balances' => $this->formattedData($query->paginate(self::$perPage), $request),
            'total_debit' => currencyFormat($this->getTotalDebit($request)->sum('amount')),
            'total_credit' => currencyFormat($this->getTotalCredit($request)->sum('amount')),
        ];
    }


    private function getFilteredTotal(string $type, Request $request): Builder
    {
        $query = $this->accountTransactionRepository->findByType($type);

        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->input('branch_id'));
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
