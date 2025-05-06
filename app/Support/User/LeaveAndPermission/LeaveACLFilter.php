<?php

namespace App\Support\User\LeaveAndPermission;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Laravel\Scout\Builder as ScoutBuilder;

class LeaveACLFilter
{
    public static function apply(Builder|EloquentBuilder|ScoutBuilder $query, Request $request)
    {
        if ($request->user()->hasAnyRole(['NOC Supervisor', 'NOC Staff'])) {
            $query->whereHas('user.roles', function ($query) {
                $query->whereIn('name', ['NOC Supervisor', 'NOC Staff']);
            })->whereHas('user', function ($query) use ($request) {
                $query->where('branch_id', 1)
                    ->orWhere('branch_id', null);
            });
        }


        if ($request->user()->hasRole('Operational Manager')) {
            $query->whereHas('user.roles', function ($query) {
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
                    'Head Of Electrical Engineer',
                    'Mechanic Senior Staff',
                ]);
            })->whereHas('user', function ($query) use ($request) {
                $query->where('branch_id', 1)
                    ->orWhere('branch_id', null);
            });
        }


        if ($request->user()->hasRole('General Manager')) {
            $query->whereHas('user.roles', function ($query) {
                $query->whereIn('name', [
                    'Inventory Controller Supervisor',
                    'Stocker Supervisor',
                    'Stocker Staff',
                ]);
            });
        }


        if ($request->user()->hasRole('FA & Tax Manager')) {
            $query->whereHas('user.roles', function ($query) {
                $query->whereIn('name', [
                    'Tax Admin Supervisor',
                    'Customer Payment Supervisor',
                    'Finance & Accounting Staff',
                    'Electrical Senior Engineer',
                    'NOC Supervisor',
                    'Customer Service Staff',
                    'Billing Admin Supervisor',
                    'Finance & Accounting Supervisor',
                    'Stocker Staff',
                    'Warehouse Security',
                    'NOC Staff',
                    'Inventory Controller Supervisor',
                    'FA Senior Staff',
                    'HR & Operational Staff',
                    'Welding Senior Engineer',
                    'Warehouse Stocker Staff',
                    'Customer Service Leader',
                ]);
            })->whereHas('user', function ($query) use ($request) {
                $query->where('branch_id', 1)
                    ->orWhere('branch_id', null);
            });;
        }


        if ($request->user()->hasAnyRole(['Head Engineer', 'Senior Engineer'])) {
            $query->whereHas('user.userHasArea', function ($query) use ($request) {
                $query->where('area_id', $request->user()->userHasArea->area_id);
            })->where(function ($query) use ($request) {
                $query->whereHas('user.branch', function ($query) use ($request) {
                    $query->where('branch_id', $request->user()->branch_id)
                        ->where('active', 1);
                });
            });
        }


        if ($request->user()->hasRole('Warehouse Supervisor')) {
            return $query->whereHas('user.roles', function ($query) use ($request) {
                $query->whereIn('name', ['Warehouse Stocker Staff', 'Warehouse Security']);
            });
        }

        if ($request->user()->hasAnyRole('Customer Service Leader')) {
            return $query->whereHas('user.roles', function ($query) use ($request) {
                $query->whereIn('name', ['Customer Service Leader', 'Customer Service Staff', 'After Sales Customer Service']);
            })->where(function ($query) use ($request) {
                $query->whereNull('branch_id')->orWhereIn('branch_id', [1])
                    ->where('active', 1);
            });
        }


        if ($request->user()->hasAnyRole('Finance & Accounting Supervisor')) {
            $query->whereHas('user.roles', function ($query) use ($request) {
                $query->whereIn('name', ['Finance & Accounting Supervisor', 'Finance & Accounting Staff', 'Tax Admin Supervisor', 'Billing Admin Supervisor', 'Customer Payment Supervisor', 'FA Senior Staff', 'Stocker Staff', 'Inventory Controller Supervisor']);
            })->where(function ($query) use ($request) {
                $query->whereHas('branch', function ($query) use ($request) {
                    $query->whereNull('branch_id')->orWhereIn('branch_id', [1])->where('active', 1);
                });
            });
        }


        if ($request->user()->hasAnyRole('Inventory Controller Supervisor')) {
            $query->whereHas('user.roles', function ($query) use ($request) {
                $query->whereIn('name', ['Stocker Staff', 'Inventory Controller Supervisor']);
            })->where(function ($query) use ($request) {
                $query->whereHas('user.branch', function ($query) use ($request) {
                    $query->where('branch_id', [1])->where('active', 1);
                });
            });
        }


        if ($request->user()->hasAnyRole('Head Of Electrical Engineer')) {
            $query->whereHas('user.roles', function ($query) use ($request) {
                $query->whereIn('name', ['Head Of Electrical Engineer', 'Senior Electrical Engineer']);
            });
        }


        if ($request->user()->hasRole('Branch Manager')) {
            $query->whereHas('user.branch', function ($query) use ($request) {
                $query->where('branch_id', $request->user()->branch_id);
            });
        }


        if ($request->user()->hasRole('KU Head Engineer')) {
            $query->whereHas('user.roles', function ($query) use ($request) {
                $query->whereIn('name', ['KU Head Engineer', 'KU Engineer'])->where('branch_id', 1);
            });
        }


        if ($request->user()->hasRole('Customer Service Supervisor')) {
            $query->whereHas('user.roles', function ($query) use ($request) {
                $query->whereIn('name', [
                    'Customer Service Leader',
                    'Customer Service Staff',
                    'After Sales Customer Service',
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
                    'Graphic Designer & Socmed Admin',
                    'Warehouse Supervisor'
                ]);
            });
        }


        if ($request->user()->hasRole('Quality Controller Supervisor')) {
            $query->whereHas('user.roles', function ($query) use ($request) {
                $query->whereIn('name', ['Quality Controller Supervisor', 'Quality Control Staff']);
            });
        }


        if ($request->user()->hasRole('Mechanic Senior Staff')) {
            $query->whereHas('user.roles', function ($query) use ($request) {
                $query->whereIn('name', ['Mechanic Senior Staff', 'Mechanic Helper Staff']);
            });
        }

        if ($request->user()->company_id === 3) {
            $query->whereHas('user.company', function ($query) use ($request) {
                $query->where('id', $request->user()->company_id);
            });
        }

        return $query;
    }


}
