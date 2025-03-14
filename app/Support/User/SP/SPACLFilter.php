<?php

namespace App\Support\User\SP;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class SPACLFilter
{
    public static function apply(Builder|EloquentBuilder $query, Request $request): EloquentBuilder|Builder
    {
        if ($request->user()->hasRole('Branch Manager')) {
            $query->where('branch_id', Auth::user()->branch_id);
        }

        return $query;
    }
}
