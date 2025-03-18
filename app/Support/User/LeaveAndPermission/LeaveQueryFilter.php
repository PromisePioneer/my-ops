<?php

namespace App\Support\User\LeaveAndPermission;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;

class LeaveQueryFilter
{
    public static function apply(Builder|EloquentBuilder $query, Request $request): EloquentBuilder|Builder
    {
        if ($request->filled('branch_id')) {
            $query->whereHas('user', function ($query) use ($request) {
                $query->where('branch_id', $request->input('branch_id'));
            });
        }

        if ($request->filled('year')) {
            $query->whereYear('start_date', $request->input('year'));
        }

        if ($request->filled('month')) {
            $query->whereMonth('start_date', $request->input('month'));
        }

        return $query;
    }
}
