<?php

namespace App\Service\Accounts;

use App\Models\Account;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class AccountService
{
    private static int $perPage = 10;

    public function data(): LengthAwarePaginator
    {
        $accounts = Account::with('children', 'accountTransaction')
            ->where('parent_id', null)
            ->paginate(self::$perPage);
        return self::formatAccounts($accounts);
    }


    private static function formatAccounts(LengthAwarePaginator $accounts): LengthAwarePaginator
    {
        $formattedAccounts = $accounts->getCollection()->map(static function ($account) {
            if ($account->children->count() > 0) {
                $initialBalance = $account->children->sum(function ($transaction) {
                    return $transaction->accountTransaction()
                        ->whereYear('date', Carbon::now()->year)->sum('amount');
                });
            } else {
                $initialBalance = $account->accountTransaction()
                    ->whereYear('date', Carbon::now()->year)->sum('amount');
            }


            return [
                'account_id' => $account->id,
                'account_code' => $account->code,
                'account_name' => $account->name,
                'balance' => 'Rp'.number_format($initialBalance, 2),
                'sub_accounts' => $account->children->map(static function ($subAccount) {
                    return [
                        'sub_account_id' => $subAccount->id,
                        'sub_account_code' => $subAccount->code,
                        'sub_account_name' => $subAccount->name,
                        'balance' => 'Rp.'.number_format(
                                $subAccount->accountTransaction()
                                    ->whereYear('date', Carbon::now()->year)->sum('amount'),
                                2
                            ),
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
                $query->where('code', 'like', '%'.$search.'%')
                    ->orWhere('name', 'like', '%'.$search.'%');
            });
        }
        $accounts = $query->paginate(self::$perPage);
        return self::formatAccounts($accounts);
    }


    public function filter()
    {
    }


}