<?php

namespace App\Support\Accounts\Interface;

use Illuminate\Database\Eloquent\Builder;

interface AccountRepositoryInterface
{
    public function getKasAccounts(Builder $query);

    public function getAssetAccounts(Builder $query);
}
