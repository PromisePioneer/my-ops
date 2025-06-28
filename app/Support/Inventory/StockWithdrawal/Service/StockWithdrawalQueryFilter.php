<?php

namespace App\Support\Inventory\StockWithdrawal\Service;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;


class StockWithdrawalQueryFilter
{
    public static function apply(QueryBuilder|EloquentBuilder $query, $request): EloquentBuilder|QueryBuilder
    {
        if (!empty($request->user()->branch_id)) {
            return $query->where('branch_id', $request->user()->branch_id);
        }

        return $query;
    }
}
