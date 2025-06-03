<?php

namespace App\Support\Attendances\AttendanceSummary;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;

class AttendanceQueryFilter
{
    public static function apply(Builder|EloquentBuilder $query, Request $request): EloquentBuilder|Builder
    {
        if ($request->filled('branch_id')) {
            $query->whereHas('branch', function ($query) use ($request) {
                $query->where('id', $request->input('branch_id'));
            });
        }


        if ($request->filled('role_id')) {
            $query->whereHas('roles', function ($query) use ($request) {
                $query->where('id', $request->input('role_id'));
            });
        }

        return $query;
    }
}
