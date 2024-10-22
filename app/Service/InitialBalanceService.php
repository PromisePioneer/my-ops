<?php

namespace App\Service;

use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class InitialBalanceService
{
    private static int $perPage = 10;

    public function data(Request $request): LengthAwarePaginator
    {
        $data = Account::whereNull('parent_id')
            ->leftJoin('account_transactions', 'account_transactions.account_id', '=', 'accounts.id')
            ->select('accounts.id', 'accounts.name', 'accounts.code')
            ->selectRaw('SUM(account_transactions.amount) as total_amount')
            ->groupBy('accounts.id', 'accounts.name', 'accounts.code')
            ->paginate(self::$perPage);

        return $this->formattedData($data);
    }

    public function filter(Request $request): LengthAwarePaginator
    {
        $year = $request->input('year');
        $branchId = $request->input('branch_id');

        $data = Account::whereNull('parent_id')
            ->leftJoin('account_transactions', 'account_transactions.account_id', '=', 'accounts.id')
            ->select('accounts.id', 'accounts.name', 'accounts.code')
            ->selectRaw('SUM(account_transactions.amount) as total_amount')
            ->groupBy('accounts.id', 'accounts.name', 'accounts.code');

        if ($year) {
            $data->whereYear('account_transactions.date', $year);
        }
        if ($branchId) {
            $data->where('account_transactions.branch_id', $branchId);
        }

        $paginatedData = $data->paginate(self::$perPage);
        return $this->formattedData($paginatedData);
    }

    public function formattedData(LengthAwarePaginator $account): LengthAwarePaginator
    {
        $data = $account->getCollection()->map(function ($account) {
            return [
                'id' => $account->id,
                'account' => $account->code.' - '.$account->name,
                'initial_balance' => number_format($account->total_amount, 2) ?? 0,
            ];
        });

        $account->setCollection($data);
        return $account;
    }

}