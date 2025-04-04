<?php

namespace App\Support\Master\Accounting\Accounts\Service;

use AllowDynamicProperties;
use App\Models\Account;
use App\Support\Master\Accounting\Accounts\Interface\AccountServiceInterface;
use App\Support\Master\Accounting\Accounts\Repositories\AccountRepository;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

#[AllowDynamicProperties] class AccountService implements AccountServiceInterface
{
    private static int $perPage = 10;


    public function __construct()
    {
        $this->accountRepository = new AccountRepository();
    }

    public function data(): LengthAwarePaginator
    {
        $accounts = Account::with('children', 'accountTransaction')
            ->where('parent_id', null)
            ->paginate(self::$perPage);
        return self::formatAccounts($accounts);
    }

    public static function formatAccounts(LengthAwarePaginator $accounts): LengthAwarePaginator
    {
        $formattedAccounts = $accounts->getCollection()->map(static function ($account) {
            if ($account->children->count() > 0) {
                $initialBalance = $account->children->sum(function ($transaction) {
                    return $transaction->accountTransaction()
                        ->whereYear('date', Carbon::now()->subYear())->sum('amount');
                });
            } else {
                $initialBalance = $account->accountTransaction()
                    ->whereYear('date', Carbon::now()->subYear())->sum('amount');
            }


            return [
                'account_id' => $account->id,
                'account_code' => $account->code,
                'account_name' => $account->name,
                'sub_accounts' => $account->children->map(static function ($subAccount) {
                    return [
                        'sub_account_id' => $subAccount->id,
                        'sub_account_code' => $subAccount->code,
                        'sub_account_name' => $subAccount->name,
                    ];
                }),
            ];
        });
        $accounts->setCollection($formattedAccounts);
        return $accounts;
    }


    public function search(Request $request): LengthAwarePaginator
    {
        $search = $request->input('search');
        $query = Account::with('children');

        if (!empty($search)) {
            $query->where(function ($query) use ($search) {
                $query->where('code', 'like', '%' . $search . '%')
                    ->orWhere('name', 'like', '%' . $search . '%');
            });
        }
        $accounts = $query->paginate(self::$perPage);
        return self::formatAccounts($accounts);
    }


    public function filter()
    {
    }


    public function getAccounts(Request $request): array
    {
        $search = $request->input('search');
        $query = Account::search($search)
            ->query(fn($query) => $query->orderby('code')->select('id', 'name', 'code'))
            ->get();

        return $query->map(function ($c) {
            return [
                'id' => $c->id,
                'text' => $c->code . ' ' . $c->name,
            ];
        })->toArray();
    }


    public function getAssetAccounts(Request $request): array
    {
        $search = $request->input('search');
        $query = Account::search($search)
            ->query(fn($query) => $this->accountRepository->getAssetAccounts($query))
            ->get();

        return $query->map(function ($c) {
            return [
                'id' => $c->id,
                'text' => $c->code . ' ' . $c->name,
            ];
        })->toArray();
    }

    public function kasAndLeverageAccounts(Request $request): array
    {
        $search = $request->input('search');
        $query = Account::search($search)
            ->query(fn($query) => $this->accountRepository->getKasAndLeverageAccounts($query))
            ->get();


        return $query->map(function ($c) {
            return [
                'id' => $c->id,
                'text' => $c->code . ' ' . $c->name,
            ];
        })->toArray();
    }


    public function getStockAccounts(Request $request): array
    {
        $search = $request->input('search');
        $query = Account::search($search)
            ->query(fn($query) => $this->accountRepository->getStockAccounts($query))
            ->get();

        return $query->map(function ($c) {
            return [
                'id' => $c->id,
                'text' => $c->code . ' ' . $c->name,
            ];
        })->toArray();
    }


    public function kasAccounts(Request $request): array
    {
        $search = $request->input('search');
        $query = Account::search($search)
            ->query(fn($query) => $this->accountRepository->getKasAccounts($query))
            ->get();

        return $query->map(function ($c) {
            return [
                'id' => $c->id,
                'text' => $c->code . ' ' . $c->name,
            ];
        })->toArray();
    }


}
