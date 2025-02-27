<?php

namespace App\Service\Transactions;

use Illuminate\Database\Eloquent\Builder as Eloquentbuilder;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;


class TransactionQueryFilter
{
    public static function apply(Eloquentbuilder|Builder $query, Request $request): Eloquentbuilder|Builder
    {
        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->input('branch_id'));
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('start_date', [$request->input('start_date'), $request->input('end_date')]);
        }

        return $query;
    }
}
