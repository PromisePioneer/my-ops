<?php

namespace App\Support\Master\Accounting\InitialBalances\Interface;

use Illuminate\Database\Eloquent\Builder;

interface InitialBalanceRepositoryInterface
{
    public function handle(): Builder;
}
