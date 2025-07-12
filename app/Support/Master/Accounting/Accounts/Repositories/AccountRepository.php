<?php

namespace App\Support\Master\Accounting\Accounts\Repositories;

use AllowDynamicProperties;
use App\Models\Account;
use App\Models\AccountCategory;
use App\Models\Company;
use App\Models\DraftStock;
use App\Support\Master\Accounting\Accounts\Interface\AccountRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

#[AllowDynamicProperties] class AccountRepository
{
    public function __construct()
    {
        $this->account = new Account();
        $this->company = new Company();
    }


    public function dataQuery(Request $request)
    {
        return Account::with(['children', 'company'])
            ->where('parent_id', null)
            ->whereHas('company', function (Builder $query) use ($request) {
                $query->where(
                    'id',
                    $this->company
                        ->where('id',
                            $request->user()->company_id
                        )->first()
                        ->id
                );
            })->orderBy('code');
    }


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


    public static function findByTransactionId(DraftStock $draftStock): Account
    {
        return Account::find($draftStock->transaction?->item?->asset_account_id);
    }


    public function findByCode(string $code)
    {
        return $this->account->query()->where('code', $code);
    }

    public function findById(int $id)
    {
        return $this->account->query()->find($id);
    }

    public function getParentAccount($query)
    {
        return $query->where('parent_id', null)->orderBy('code');
    }


    public function getAccountCategories()
    {
        $data = AccountCategory::with('children')->get();


    }
}
