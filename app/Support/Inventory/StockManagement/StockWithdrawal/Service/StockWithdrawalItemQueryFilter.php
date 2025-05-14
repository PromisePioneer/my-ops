<?php

namespace App\Support\Inventory\StockManagement\StockWithdrawal\Service;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;

class StockWithdrawalItemQueryFilter
{
    public static function apply(Builder|EloquentBuilder $query, Request $request): Builder|EloquentBuilder
    {
        if ($request->filled('branch_id')) {
            $query->whereHas('stockWithdrawal', function ($query) use ($request) {
                $query->where('branch_id', $request->input('branch_id'));
            });
        }

        return $query;
    }
}
