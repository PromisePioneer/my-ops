<?php

namespace App\Support\Master\Accounting\InitialBalances\Repositories;

use App\Models\Account;
use App\Support\Master\Accounting\InitialBalances\Interface\InitialBalanceRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;

class InitialBalanceRepository implements InitialBalanceRepositoryInterface
{
    public function handle(): Builder
    {
        return Account::with('accountTransaction', 'children', 'parent')
            ->whereNull('parent_id');
    }




}
