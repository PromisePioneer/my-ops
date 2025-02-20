<?php

namespace App\Service\User\ContractManagement;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;

class ContractQueryFilter
{
    public static function apply(EloquentBuilder|Builder $query, Request $request): EloquentBuilder|Builder
    {
        if ($request->filled('branch_id')) {
            $query->whereHas('user', function ($query) use ($request) {
                $query->where('branch_id', $request->input('branch_id'));
            });
        }

        if ($request->filled('year')) {
            $query->whereYear('end_date', $request->input('year'));
        }

        if ($request->filled('month')) {
            $query->whereYear('end_date', $request->input('month'));
        }
        return $query;
    }

}
