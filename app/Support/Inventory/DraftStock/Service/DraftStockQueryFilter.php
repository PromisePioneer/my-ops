<?php

namespace App\Support\Inventory\StockManagement\DraftStock\Service;

namespace App\Support\Inventory\DraftStock\Service;

use App\Models\Master\Common\Branch;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;

class DraftStockQueryFilter
{

    public static function apply(Builder|EloquentBuilder $query, Request $request): EloquentBuilder|Builder
    {
        if ($request->filled('branch_id')) {
            $branch = Branch::with('children')->find($request->input('branch_id'));
            $children = [];
            foreach ($branch->children as $child) {
                $children[] = $child->id;
            }
            $query->whereHas('transaction', function ($query) use ($children) {
                $query->whereIn('branch_id', $children);
            });
        }

        return $query;
    }
}
