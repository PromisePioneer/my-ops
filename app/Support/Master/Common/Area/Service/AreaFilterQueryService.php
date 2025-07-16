<?php

namespace App\Support\Master\Common\Area\Service;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;

class AreaFilterQueryService
{
    public static function apply(EloquentBuilder|Builder $query, Request $request): EloquentBuilder|Builder
    {
        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->input('branch_id'));
        }

        return $query;
    }
}
