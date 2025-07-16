<?php

namespace App\Support\Master\Accounting\Assets\Service;

use Illuminate\Database\Query\Builder;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Http\Request;


class AssetQueryFilterService
{
    public static function apply(Builder|EloquentBuilder $query, Request $request): Builder|EloquentBuilder
    {
        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->get('branch_id'));
        }

        if ($request->filled('item_id')) {
            $query->where('item_id', $request->get('item_id'));
        }


        return $query;
    }
}
