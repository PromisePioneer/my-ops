<?php

namespace App\Support\Master\Accounting\Accounts\Repositories;

use App\Support\Master\Accounting\Accounts\Interface\AccountRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;

class AccountRepository implements AccountRepositoryInterface
{
    public function getAssetAccounts(Builder $query): Builder
    {
        return $query->whereIn('code', ['121', '122', '123', '125', '126'])
            ->orderby('code')
            ->select('id', 'name', 'code');
    }

    public function getKasAndLeverageAccounts(Builder $query): Builder
    {
        return $query->whereIn('code', [
            '111-01',
            '111-02',
            '111-03',
            '111-04',
            '211',
            '216',
            '221',
            '222',
            '223'
        ])
            ->orderBy('code')
            ->select('id', 'name', 'code');
    }


    public function getStockAccounts(Builder $query): Builder
    {
        return $query->whereIn('code', ['112-01', '112-02'])
            ->orderBy('code')
            ->select('id', 'name', 'code');
    }


    public function getKasAccounts(Builder $query): Builder
    {
        return $query->whereIn('code', ['111-01', '112-02', '111-03', '111-04'])
            ->orderBy('code')
            ->select('id', 'name', 'code');
    }
}
