<?php

namespace App\Support\Transactions\Services;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder;
use Laravel\Scout\Builder as ScoutBuilder;
use Illuminate\Http\Request;

class TransactionACLFilter
{
    public static function apply(Builder|EloquentBuilder|ScoutBuilder $query, Request $request): Builder|EloquentBuilder|ScoutBuilder
    {
        if ($request->user()->hasAnyRole(['Branch Manager', 'Finance & Accounting Staff'])) {
            $query->where('branch_id', $request->user()->branch_id);
        }

        return $query;
    }
}
