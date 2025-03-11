<?php

namespace App\Service\User\LeaveAndPermission;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;

class LeaveSelect2QueryFilter
{
    public static function apply(Builder|EloquentBuilder $query, Request $request)
    {
        if ($request->user()->hasAnyRole(['NOC Supervisor', 'NOC Staff'])) {
            return $query->whereHas('roles', function ($query) {
                $query->whereIn('name', ['NOC Supervisor', 'NOC Staff']);
            })->where(function ($query) {
                $query->whereNull('branch_id')->orWhere('branch_id', 1);
            });
        }

        if ($request->user()->hasAnyRole('Super Admin', 'Operational Manager', 'Director', 'Main Commissioner')) {
            return $query;
        }

        if ($request->user()->Role('Operational Manager')) {
            return $query->whereHas('roles', function ($query) {
                $query->whereIn('name', [
                    'NOC Supervisor', 'Programmer', 'Project Controller & Vendor Supervisor',
                    'Stocker Supervisor', 'Quality Controller Supervisor', 'Graphic Designer & Socmed Admin',
                    'After Sales Customer Service', 'Legal & Corporate Commissioner', 'Mechanic Senior Staff', 'Head Engineer'
                ]);
            });
        }


        if ($request->user()->hasAnyRole(['Head Engineer', 'Senior Engineer'])) {
            return $query->whereHas('userHasArea', function ($query) use ($request) {
                $query->where('area_id', $request->user()->userHasArea->area_id);
            })->where(function ($query) use ($request) {
                $query->whereHas('branch', function ($query) use ($request) {
                    $query->where('branch_id', $request->user()->branch_id);
                })->where('active', 1);
            });
        }

        if ($request->user()->hasAnyRole('Customer Service Leader')) {
            return $query->whereHas('roles', function ($query) use ($request) {
                $query->whereIn('name', ['Customer Service Leader', 'Customer Service Staff', 'After Sales Customer Service']);
            })->where(function ($query) use ($request) {
                $query->where('active', 1)->whereNull('branch_id')->orWhereIn('branch_id', [1]);
            });
        }


        if ($request->user()->hasAnyRole(['Finance & Accounting Supervisor', 'FA & Tax Manager'])) {
            return $query->whereHas('roles', function ($query) use ($request) {
                $query->whereIn('name', ['Finance & Accounting Supervisor', 'Finance & Accounting Staff', 'Tax Admin Supervisor', 'Billing Admin Supervisor', 'Customer Payment Supervisor', 'FA Senior Staff', 'Stocker Staff', 'Inventory Controller Supervisor']);
            })->where(function ($query) use ($request) {
                $query->where('active', 1)->whereNull('branch_id')->orWhereIn('branch_id', [1]);
            });
        }


        if ($request->user()->hasAnyRole('Head Of Electrical Engineer')) {
            return $query->whereHas('roles', function ($query) use ($request) {
                $query->whereIn('name', ['Head Of Electrical Engineer', 'Senior Electrical Engineer']);
            })->whereHas('branch', function ($query) use ($request) {
                $query->whereNull('branch_id');
            });
        }


        if ($request->user()->hasRole('Branch Manager')) {
            return $query->where('branch_id', $request->user()->branch_id)->where('active', '=', 1);
        }


        if ($request->user()->hasRole('KU Head Engineer')) {
            return $query->whereHas('roles', function ($query) use ($request) {
                $query->whereIn('name', ['KU Head Engineer', 'KU Engineer']);
            });
        }


        if ($request->user()->hasRole('Quality Controller Supervisor')) {
            return $query->whereHas('roles', function ($query) use ($request) {
                $query->whereIn('name', ['Quality Controller Supervisor', 'Quality Control Staff']);
            });
        }

        return $query;
    }
}
