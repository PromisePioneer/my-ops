<?php

namespace App\Support\Master\Accounting\InitialBalances\Service;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;

class InitialBalanceQueryFilter
{
    public static function apply(Builder|EloquentBuilder $query, Request $request): Builder|EloquentBuilder
    {
        if ($request->filled('branch_id')) {
            $query->whereHas('accountTransaction', function (EloquentBuilder $query) use ($request) {
                $query->where('branch_id', $request->input('branch_id'))
                    ->where('transaction_type', 'SA');
            });
        }

        return $query;
    }
}
