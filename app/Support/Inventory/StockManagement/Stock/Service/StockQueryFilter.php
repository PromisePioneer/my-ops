<?php

namespace App\Support\Inventory\StockManagement\Stock\Service;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;


class StockQueryFilter
{
    public static function apply(Builder|EloquentBuilder $query, Request $request): EloquentBuilder|Builder
    {
        if ($request->filled('branch_id')) {
            $query->with('stock', function ($query) use ($request) {
                $query->where('branch_id', $request->input('branch_id'));
            });
        }

        return $query;
    }
}
