<?php

namespace App\Support\Master\Accounting\InitialBalances\Interface;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

interface InitialBalanceRepositoryInterface
{
    public function handle(Request $request): Builder;
}
