<?php

namespace App\Service\Master\General\Area;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;


class AreaACLQuery
{
    public static function apply(EloquentBuilder|Builder $query, Request $request): EloquentBuilder|Builder
    {
        if ($request->user()->hasRole('Head Engineer')) {
            $query->whereHas('areaHasUser.user', function ($query) use ($request) {
                $query->where('user_id', $request->user()->id);
            });
        }
        if ($request->user()->hasRole('Branch Manager')) {
            $query->where('branch_id', $request->user()->branch_id);
        }

        if ($request->user()->hasRole('Project Controller & Vendor Supervisor')) {
            $query->whereHas('department', function ($query) use ($request) {
                $query->where('name', 'Vendor');
            });
        }


        return $query;
    }
}
