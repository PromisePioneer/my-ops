<?php

namespace App\Support\Utility\ActivityLog\Service;

use Carbon\Carbon;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Http\Request;

class ActivityLogQueryFilter
{
    public static function apply(EloquentBuilder|Builder $query, Request $request): Builder|EloquentBuilder
    {



        if ($request->filled('branch_id')) {
            $query->whereHas('causer', function (Builder|EloquentBuilder $query) use ($request) {
                $query->where('branch_id', $request->branch_id);
            });
        }


        if ($request->filled('role_id')) {
            $query->whereHas('roles', function (Builder|EloquentBuilder $query) use ($request) {
            });
        }

        return $query;
    }
}
