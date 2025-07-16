<?php

namespace App\Support\Master\Operational\InitialInventoryBalance\Service;

use Illuminate\Database\Query\Builder;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

class InitialInventoryBalanceQueryFilter
{
    public static function apply(Builder|EloquentBuilder $query, $request): Builder|EloquentBuilder
    {
        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->input('branch_id'));
        }

        return $query;
    }
}
