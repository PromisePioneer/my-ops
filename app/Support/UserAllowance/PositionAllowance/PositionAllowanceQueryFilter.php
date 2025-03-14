<?php

namespace App\Support\UserAllowance\PositionAllowance;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;


class PositionAllowanceQueryFilter
{
    public static function apply(Builder|EloquentBuilder $query, Request $request): Builder|EloquentBuilder
    {
        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->input('branch_id'));
        }

//        if ($request->filled('role_id')) {
//            $query->whereHas('roles', function ($query) use ($request) {
//                $query->where('role_id', $request->input('role_id'));
//            });
//        }

        return $query;
    }
}
