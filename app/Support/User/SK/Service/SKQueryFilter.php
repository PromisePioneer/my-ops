<?php

namespace App\Support\User\SK\Service;

use Illuminate\Contracts\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;

class SKQueryFilter
{
    public static function apply(Builder|EloquentBuilder $query, Request $request): Builder|EloquentBuilder
    {
        if ($request->filled('sk_type')) {
            $query->where('sk_type', $request->input('sk_type'));
        }

        return $query;
    }
}
