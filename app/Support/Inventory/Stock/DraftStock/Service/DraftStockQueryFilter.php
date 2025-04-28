<?php

namespace App\Support\Inventory\Stock\DraftStock\Service;

use App\Models\Master\Common\Branch;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;

class DraftStockQueryFilter
{

    public static function apply(Builder|EloquentBuilder $query, Request $request): EloquentBuilder|Builder
    {
        if ($request->filled('branch_id')) {
            $branchId = $request->input('branch_id');

            $branchIds = Branch::where('id', $branchId)
                ->orWhere('parent_id', $branchId)
                ->pluck('id');

            $query->with('branch.parent')
                ->whereIn('branch_id', $branchIds);
        }

        return $query;
    }
}
