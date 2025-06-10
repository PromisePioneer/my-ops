<?php

namespace App\Support\Master\Accounting\Accounts\Repositories;

use App\Models\Account;
use App\Models\DraftStock;
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
        return $query->with('children')->with('children')
            ->whereIn('code', ['111', '211', '221', '222', '223'])->orderBy('code')
            ->select('id', 'name', 'code');
    }


    public function getStockAccounts(Builder $query): Builder
    {
        return $query->with('children')
            ->whereHas('parent', function (Builder $query) {
                $query->where('code', '112');
            })->orderBy('code')
            ->select('id', 'name', 'code');
    }


    public function getKasAccounts(Builder $query): Builder
    {
        return $query->whereIn('code', ['111-01', '112-02', '111-03', '111-04'])
            ->orderBy('code')
            ->select('id', 'name', 'code');
    }


    public static function findByTransactionOrInitialInventoryBalanceId(DraftStock $draftStock): Account
    {
        return Account::find(
            $draftStock->transaction?->item?->asset_account_id
            ?? $draftStock->initialInventoryBalance?->item?->asset_account_id
        );
    }
}
