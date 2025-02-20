<?php

namespace App\Service\User\SP;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;

class SPQueryFilter
{
    public static function apply(EloquentBuilder|Builder $query, Request $request): EloquentBuilder|Builder
    {
        if ($request->filled('branch_id')) {
            $query->whereHas('user', function ($query) use ($request) {
                $query->where('id', $request->input('branch_id'));
            });
        }

        if ($request->filled('year')) {
            $query->whereYear('start_date', $request->input('year'));
        }

        if ($request->filled('month')) {
            $query->whereMonth('month', $request->input('month'));
        }

        if ($request->filled('sp_type')) {
            $query->where('sp_type', $request->input('sp_type'));
        }

        return $query;
    }
}
