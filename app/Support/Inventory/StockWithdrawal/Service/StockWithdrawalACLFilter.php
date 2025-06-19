<?php

namespace App\Support\Inventory\StockWithdrawal\Service;

use App\Models\Master\Common\Branch;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Http\Request;

class StockWithdrawalACLFilter
{
    public static function apply(EloquentBuilder|QueryBuilder $query, Request $request)
    {
        $branch = Branch::with('children')->find($request->user()->branch_id);
        if (!empty($request->user()->branch_id)) {
            $query->whereHas('branch', function ($query) use ($branch) {
                $query->whereIn('id', $branch->children->pluck('id')->toArray());
            });
        }


        return $query;
    }
}
