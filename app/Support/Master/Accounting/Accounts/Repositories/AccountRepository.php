<?php

namespace App\Support\Master\Accounting\Accounts\Repositories;

use AllowDynamicProperties;
use App\Models\Account;
use App\Models\Company;
use App\Models\DraftStock;
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
        return Account::with(['children' => function ($q) {
            $q->orderBy('code');
        }, 'company'])
            ->where('parent_id', null)
            ->whereHas('company', function (Builder $query) use ($request) {
                $query->where(
                    'id',
                    $this->company
                        ->where('id',
                            $request->session()->get('company_session')
                        )->first()
                        ->id
                );
            })->orderBy('code');
    }


    public function getAssetAccounts(Request $request, ?string $search): Builder
    {
        $accounts = $this->account->with('children')->whereIn('code', ['125', '126'])
            ->where('company_id', $request->session()->get('company_session'))
            ->orderby('code')
            ->select('id', 'name', 'code');

        if (!empty($search)) {
            $accounts->where(function ($query) use ($search) {
                $query->whereHas('children', function ($query) use ($search) {
                    $query->where('code', 'like', '%' . $search . '%')
                        ->orWhere('name', 'like', '%' . $search . '%');
                });
            });
        }

        return $accounts;
    }

    public function getKasAndLeverageAccounts(Request $request, ?string $search)
    {
        $accounts = $this->account->with('children')
            ->where('company_id', $request->session()->get('company_session'))
            ->whereIn('code', ['111', '211', '221', '222', '223'])
            ->orderBy('code')
            ->select('id', 'name', 'code');


        if (!empty($search)) {
            $accounts->whereHas('children', function ($query) use ($search) {
                $query->where('code', 'like', '%' . $search . '%')
                    ->orWhere('name', 'like', '%' . $search . '%');
            });
        }

        return $accounts;
    }


    public function getStockAccounts(Request $request, ?string $search): Builder
    {
        $accounts = $this->account->with('children')
            ->where('company_id', $request->session()->get('company_session'))
            ->where('code', '112')
            ->orderBy('code')
            ->select('id', 'name', 'code');


        if (!empty($search)) {
            $accounts->whereHas('children', function ($query) use ($search) {
                $query->where('code', 'like', '%' . $search . '%')
                    ->orWhere('name', 'like', '%' . $search . '%');
            });
        }


        return $accounts;
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
}
