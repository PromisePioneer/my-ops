<?php

namespace App\Support\FpDevice;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;

class FpDeviceQueryFilter
{
    public static function apply(Builder|EloquentBuilder $query, Request $request): Builder|EloquentBuilder
    {
        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->input('branch_id'));
        }

        return $query;
    }
}
