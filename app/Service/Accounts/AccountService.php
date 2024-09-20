<?php

namespace App\Service\Accounts;

use App\Models\Account;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountService
{
    private static int $perPage = 10;

    public function data(?int $branchId): LengthAwarePaginator
    {
        $accounts = Account::with([
            'subAccount' => static function ($query) {
                $query->orderBy('code', 'ASC');
            },
        ])->where('branch_id', $branchId)
            ->paginate(self::$perPage);

        return self::formatAccounts($accounts);
    }


    private static function formatAccounts(LengthAwarePaginator $accounts): LengthAwarePaginator
    {
        $formattedAccounts = $accounts->getCollection()->map(static function ($account) {
            $formattedSubAccounts = $account->subAccount->map(static function ($subAccount) {
                return [
                    'sub_account_id' => $subAccount->id,
                    'sub_account_code' => $subAccount->code,
                    'sub_account_name' => $subAccount->name,
                    'sub_account_debit_balance' => number_format($subAccount->debit_balance, 2, ',', '.'),
                    'sub_account_credit_balance' => number_format($subAccount->credit_balance, 2, ',', '.'),
                    'sub_account_balance' => number_format($subAccount->balance, 2, ',', '.'),
                ];
            });

            return [
                'account_id' => $account->id,
                'account_code' => $account->code,
                'account_name' => $account->name,
                'account_debit_balance' => number_format($account->debit_balance, 2, ',', '.'),
                'account_credit_balance' => number_format($account->credit_balance, 2, ',', '.'),
                'account_balance' => number_format($account->balance, 2, ',', '.'),
                'sub_accounts' => $formattedSubAccounts,
            ];
        });
        $accounts->setCollection($formattedAccounts);
        return $accounts;
    }


    public function search(Request $request): LengthAwarePaginator
    {
        $search = $request->input('search');
        $query = Account::with([
            'subAccount' => function ($query) {
                $query->orderBy('code', 'ASC');
            },
        ])->where('branch_id', Auth::user()->branch_id);

        if (!empty($search)) {
            $query->where(function ($query) use ($search) {
                $query->where('code', 'like', '%'.$search.'%')
                    ->orWhere('name', 'like', '%'.$search.'%');
            });
        }
        $accounts = $query->paginate(self::$perPage);
        return self::formatAccounts($accounts);
    }


    public function filterByBranch(int $branchId): LengthAwarePaginator
    {
        $accounts = Account::with([
            'subAccount' => static function ($query) {
                $query->orderBy('code', 'ASC');
            },
        ])->where('branch_id', $branchId)
            ->paginate(self::$perPage);

        return self::formatAccounts($accounts);
    }


}