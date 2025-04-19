<?php

namespace App\Support\Master\Accounting\InitialBalances\Interface;

use Illuminate\Http\Request;

interface InitialBalanceServiceInterface
{
    public function data();

    public function search(Request $request);

    public function formattedData($account, ?Request $request = null);


    public function getFilteredTransactionSum($account, $type, ?Request $request): float;

    public function filter(Request $request);
}
