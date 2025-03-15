<?php

namespace App\Support\Master\Accounting\Accounts\Interface;

use Illuminate\Database\Eloquent\Builder;

interface AccountRepositoryInterface
{
    public function getKasAccounts(Builder $query);

    public function getAssetAccounts(Builder $query);
}
