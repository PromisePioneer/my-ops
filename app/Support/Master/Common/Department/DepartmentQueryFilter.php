<?php

namespace App\Support\Master\Common\Department;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;


class DepartmentQueryFilter
{
    public static function apply(Builder|EloquentBuilder $query, Request $request): EloquentBuilder|Builder
    {
        if ($request->user()->hasAnyRole(['Branch Manager', 'Project Controller & Vendor Supervisor'])) {
            $query->whereIn('name', ['Vendor', 'Area']);
        }

        return $query;
    }
}
