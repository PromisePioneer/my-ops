<?php

namespace App\Service\User\User;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class UserQueryFilter
{
    public static function apply(Builder $query, Request $request): Builder
    {
        if ($request->input('branch_id') && !$request->user()->hasRole('Branch Manager')) {
            $query->where('branch_id', $request->input('branch_id'));
        }

        if ($request->input('company_id')) {
            $query->where('company_id', $request->input('company_id'));
        }

        if ($request->input('month')) {
            $query->whereMonth('join_date', $request->input('month'));
        }

        if ($request->input('year')) {
            $query->whereYear('join_date', $request->input('year'));
        }

        if ($request->filled('active')) {
            $query->where('active', $request->input('active'));
        }

        return $query;
    }
}
