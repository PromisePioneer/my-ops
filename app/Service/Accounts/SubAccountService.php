<?php

namespace App\Service\Accounts;

use App\Http\Requests\Master\AccountCategory\SubAccountImportRequest;
use App\Imports\SubAccountImport;
use App\Models\SubAccount;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Throwable;

class SubAccountService
{
    public function data(): LengthAwarePaginator
    {
        $query = SubAccount::whereHas('account')
            ->orderBy('code', 'ASC')
            ->paginate(10);

        self::formattedData($query);

        return $query;
    }


    private static function formattedData($query): void
    {
        $formattedData = $query->getCollection()->map(function ($item) {
            return [
                'id' => $item->id,
                'code' => $item->code,
                'name' => $item->name,
                'debit_balance' => number_format($item->debit_balance, 2, ',', '.'),
                'credit_balance' => number_format($item->credit_balance, 2, ',', '.'),
                'balance' => number_format($item->balance, 2, ',', '.'),
            ];
        });
        $query->setCollection($formattedData);
    }


    public function search(Request $request): LengthAwarePaginator
    {
        $subAccount = SubAccount::whereHas('account', static function ($query) {
            $query->where('branch_id', Auth::user()->branch_id);
        })->where('name', 'like', '%'.$request->search.'%')
            ->orWhere('code', 'like', '%'.$request->search.'%')
            ->orderBy('code', 'ASC')
            ->paginate(10);

        self::formattedData($subAccount);

        return $subAccount;
    }


    /**
     * @throws Throwable
     */
    public function import(SubAccountImportRequest $request): void
    {
        DB::transaction(function () use ($request) {
            SubAccount::join('accounts', 'accounts.id', 'sub_accounts.account_id')
                ->whereIn('accounts.branch_id', [Auth::user()->branch_id])
                ->delete();

            $file = $request->file('file_import');
            Excel::import(new SubAccountImport(), $file);
        });
    }
}