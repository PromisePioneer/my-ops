<?php

namespace App\Support\User\LeaveAndPermission;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;

class LeaveSelect2QueryFilter
{
    public static function apply(Builder|EloquentBuilder $query, Request $request): EloquentBuilder|Builder
    {
        if ($request->user()->hasAnyRole(['NOC Supervisor', 'NOC Staff'])) {
            $query->whereHas('roles', function ($query) {
                $query->whereIn('name', ['NOC Supervisor', 'NOC Staff']);
            })->where(function ($query) {
                $query->whereNull('branch_id')->orWhere('branch_id', 1);
            });
        }


        if ($request->user()->hasRole('Operational Manager')) {
            $query->whereHas('roles', function ($query) {
                $query->whereIn('name', [
                    'Head Engineer',
                    'KU Head Engineer',
                    'Quality Controller Supervisor',
                    'Backbone Team Supervisor',
                    'Trainer & Quality Control Staff',
                    'Stocker Supervisor',
                    'Programmer',
                    'Legal & Corporate Commissioner',
                    'After Sales Customer Service',
                    'Project Controller & Vendor Supervisor',
                    'Mechanic Senior Staff'
                ]);
            });
        }


        if ($request->user()->hasRole('Legal & Corporate Commissioner')) {
            $query->whereHas('roles', function ($query) {
                $query->whereIn('name', [
                    'Head Engineer',
                    'Senior Engineer',
                    'Engineer',
                    'KU Head Engineer',
                    'KU Engineer',
                    'Quality Controller Supervisor',
                    'Backbone Team Supervisor',
                    'Trainer & Quality Control Staff',
                    'Stocker Supervisor',
                    'Programmer',
                    'After Sales Customer Service',
                    'Project Controller & Vendor Supervisor',
                    'Warehouse Security',
                    'Graphic Designer & Socmed Admin'
                ]);
            });

        }


        if ($request->user()->hasRole('FA & Tax Manager')) {
            $query->whereHas('roles', function ($query) {
                $query->whereIn('name', [
                    'Tax Admin Supervisor',
                    'Customer Payment Supervisor',
                    'Finance & Accounting Staff',
                    'After Sales Customer Service',
                    'Electrical Senior Engineer',
                    'NOC Supervisor',
                    'Customer Service Staff',
                    'Billing Admin Supervisor',
                    'Finance & Accounting Supervisor',
                    'Legal & Corporate Commissioner',
                    'Stocker Supervisor',
                    'Stocker Staff',
                    'Warehouse Security',
                    'NOC Staff',
                    'Programmer',
                    'Inventory Controller Supervisor',
                    'FA Senior Staff',
                    'HR & Operational Staff',
                    'Project Controller & Vendor Supervisor',
                    'Welding Senior Engineer',
                    'Warehouse Stocker Staff',
                    'Customer Service Leader',
                    ''
                ]);
            });
        }


        if ($request->user()->hasAnyRole(['Head Engineer', 'Senior Engineer'])) {
            $query->whereHas('userHasArea', function ($query) use ($request) {
                $query->where('area_id', $request->user()->userHasArea->area_id);
            })->where(function ($query) use ($request) {
                $query->whereHas('branch', function ($query) use ($request) {
                    $query->where('branch_id', $request->user()->branch_id);
                })->where('active', 1);
            });
        }

        if ($request->user()->hasRole('Customer Service Leader')) {
            $query->whereHas('roles', function ($query) use ($request) {
                $query->whereIn('name', ['Customer Service Leader', 'Customer Service Staff', 'After Sales Customer Service']);
            })->where(function ($query) use ($request) {
                $query->where('active', 1)->whereNull('branch_id')->orWhereIn('branch_id', [1]);
            });
        }


        if ($request->user()->hasAnyRole(['Finance & Accounting Supervisor', 'FA & Tax Manager'])) {
            $query->whereHas('roles', function ($query) use ($request) {
                $query->whereIn('name', ['Finance & Accounting Supervisor', 'Finance & Accounting Staff', 'Tax Admin Supervisor', 'Billing Admin Supervisor', 'Customer Payment Supervisor', 'FA Senior Staff', 'Stocker Staff', 'Inventory Controller Supervisor']);
            })->where(function ($query) use ($request) {
                $query->where('active', 1)->whereNull('branch_id')->orWhereIn('branch_id', [1]);
            });
        }


        if ($request->user()->hasRole('Inventory Controller Supervisor')) {
            $query->whereHas('roles', function ($query) use ($request) {
                $query->whereIn('name', ['Stocker Staff', 'Inventory Controller Supervisor']);
            })->where(function ($query) use ($request) {
                $query->where('active', 1)->whereNull('branch_id')->orWhereIn('branch_id', [1]);
            });
        }


        if ($request->user()->hasAnyRole('Head Of Electrical Engineer')) {
            $query->whereHas('roles', function ($query) use ($request) {
                $query->whereIn('name', ['Head Of Electrical Engineer', 'Senior Electrical Engineer', 'Electrical Engineer']);
            })->whereNull('branch_id');
        }


        if ($request->user()->hasRole('Branch Manager')) {
            $query->where('branch_id', $request->user()->branch_id);
        }


        if ($request->user()->hasRole('KU Head Engineer')) {
            $query->whereHas('roles', function ($query) use ($request) {
                $query->whereIn('name', ['KU Head Engineer', 'KU Engineer']);
            });
        }


        if ($request->user()->hasRole('Quality Controller Supervisor')) {
            $query->whereHas('roles', function ($query) use ($request) {
                $query->whereIn('name', ['Quality Controller Supervisor', 'Quality Control Staff']);
            });
        }

        if ($request->user()->company_id === 3) {
            $query->whereHas('company', function ($query) use ($request) {
                $query->where('id', $request->user()->company_id);
            });
        }

        return $query;
    }
}
