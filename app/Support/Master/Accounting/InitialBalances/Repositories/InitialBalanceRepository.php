<?php

namespace App\Support\Master\Accounting\InitialBalances\Repositories;

use App\Models\Account;
use App\Support\Master\Accounting\InitialBalances\Interface\InitialBalanceRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class InitialBalanceRepository implements InitialBalanceRepositoryInterface
{
    public function handle(Request $request): Builder
    {
        return Account::with(['accountTransaction', 'children', 'parent'])
            ->where('company_id', $request->session()->get('company_session'))
            ->whereNull('parent_id');
    }


}
