<?php

namespace App\Support\Master\Accounting\Accounts\Interface;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

interface AccountServiceInterface
{
    public function data();

    public static function formatAccounts(LengthAwarePaginator $accounts);

    public function search(Request $request);

    public function filter();

    public function getAccounts(Request $request);

    public function getAssetAccounts(Request $request);


    public function getKasAccounts(Request $request);
}
