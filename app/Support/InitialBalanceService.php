<?php

namespace App\Support;

use App\Models\Account;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class InitialBalanceService
{
    private static int $perPage = 10;

    public function data()
    {
        $data = Account::with('accountTransaction', 'children')
            ->whereNull('parent_id')
            ->paginate(self::$perPage);

        return $this->formattedData($data);
    }


    public function search(Request $request)
    {
        $search = $request->input('search');


        $query = Account::with('accountTransaction', 'children')->whereNull('parent_id');


        if (!empty($search)) {
            $query->where('name', 'like', '%'.$search.'%')->orWhere('code', 'like', '%'.$search.'%');
        }

        $data = $query->paginate(self::$perPage);

        return $this->formattedData($data);
    }


    public function formattedData($account, ?Request $request = null)
    {
        $data = $account->getCollection()->map(function ($account) use ($request) {


            if ($account->children->count() > 0) {
                $initialBalance = $account->children->sum(function ($transaction) use ($request) {
                    return $this->getFilteredTransactionSum($transaction, 'SA', $request);
                });
            } else {
                $initialBalance = $this->getFilteredTransactionSum($account, 'SA', $request);
            }

            return [
                'id' => $account->id,
                'account' => $account->code.' '.$account->name,
                'initial_balance' => $initialBalance ? number_format($initialBalance, 2) : null,
                'sub_accounts' => $account->children->map(function ($subAccount) use ($request) {
                    return [
                        'id' => $subAccount->id,
                        'sub_account_code' => $subAccount->code,
                        'sub_account_name' => $subAccount->name,
                        'initial_balance' => $this->getFilteredTransactionSum(
                            $subAccount,
                            'SA',
                            $request
                        ) ? number_format(
                            $this->getFilteredTransactionSum($subAccount, 'SA', $request),
                            2
                        ) : null,
                    ];
                }),

            ];
        });


        $account->setCollection($data);
        return $account;
    }


    public function getFilteredTransactionSum($account, $type, ?Request $request): float
    {
        $transactions = $account->accountTransaction()
            ->whereYear('date', Carbon::now()->subYear())
            ->where('transaction_type', $type);

        if ($request?->branch_id) {
            $transactions->where('branch_id', $request->branch_id);
        }

        if ($request?->year) {
            $transactions->whereYear('date', $request->year);
        }

        if ($request?->month) {
            $transactions->whereMonth('date', $request->month);
        }

        return $transactions->sum('amount');
    }


    public function filter(Request $request)
    {
        $branch = $request->input('branch_id');
        $year = $request->input('year');

        $query = Account::with('children', 'accountTransaction')
            ->whereNull('parent_id');

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

        $data = $query->paginate(self::$perPage);
        return $this->formattedData($data, $request);
    }
}
