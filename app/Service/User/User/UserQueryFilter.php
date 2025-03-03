<?php

namespace App\Service\User\User;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class UserQueryFilter
{
    public static function apply(Builder $query, Request $request): Builder
    {
        if ($request->filled('branch_id') && !$request->user()->hasRole('Branch Manager')) {
            $query->where('branch_id', $request->input('branch_id'));
        }

        if ($request->filled('role_id')) {
            $query->where('role_id', $request->input('role_id'));
        }

        if ($request->filled('company_id')) {
            $query->where('company_id', $request->input('company_id'));
        }

        if ($request->filled('month')) {
            $query->whereMonth('join_date', $request->input('month'));
        }

        if ($request->filled('year')) {
            $query->whereYear('join_date', $request->input('year'));
        }

        if ($request->filled('active')) {
            $query->where('active', $request->input('active'));
        }

        return $query;
    }
}
