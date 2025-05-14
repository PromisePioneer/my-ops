<?php

namespace App\Support\Inventory\StockManagement\StockWithdrawal\Service;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;

class StockWithdrawalQueryFilter
{
    public static function apply(Builder|EloquentBuilder $query, Request $request): EloquentBuilder|Builder
    {
        if ($request->filled('branch_id')) {
            $query->whereHas('branch', function ($query) use ($request) {
                $query->where('id', $request->input('branch_id'));
            });
        }


        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('date', [$request->input('start_date'), $request->input('end_date')]);
        }


        return $query;
    }
}
