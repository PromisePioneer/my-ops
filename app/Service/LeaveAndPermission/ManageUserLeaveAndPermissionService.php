<?php

namespace App\Service\LeaveAndPermission;

use AllowDynamicProperties;
use App\Models\LeaveAndPermission;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use function App\Helper\formatDate;

#[AllowDynamicProperties] class ManageUserLeaveAndPermissionService
{
    private static int $perPage = 10;

    public function __construct()
    {
        $this->leaveAndPermission = new LeaveAndPermission();
    }


    public function query(): Builder
    {
        return LeaveAndPermission::with('accBy', 'user', 'user.userHasArea', 'user.branch');
    }


    public function data(Request $request): LengthAwarePaginator
    {
        $query = $this->query();
        $permissions = $this->permissionsData($request, $query);
        $query = $permissions->paginate(10);
        return self::formattedData($query);
    }


    public function filter(Request $request, ?int $branchId, ?int $year, ?int $month): LengthAwarePaginator
    {
        $query = $this->query();
        if ($request->user()->hasAnyRole('Super Admin', 'Operational Manager', 'FA & Tax Manager', 'Director', 'Main Commissioner', 'Legal & Corporate Commissioner')) {
            if ($branchId && $year && $month) {
                $query->whereHas('user.branch', function ($query) use ($branchId) {
                    $query->where('branch_id', $branchId);
                })->whereYear('start_date', $year)->whereMonth('start_date', $month);
            }

            if ($branchId && !$year && !$month) {
                $query->whereHas('user.branch', function ($query) use ($branchId) {
                    $query->where('branch_id', $branchId);
                });
            }

            if ($branchId && $year && !$month) {
                $query->whereHas('user.branch', function ($query) use ($branchId) {
                    $query->where('branch_id', $branchId);
                })->whereYear('start_date', $year);
            }
        }

        if ($year && !$month && !$branchId) {
            $query->whereYear('start_date', $year);
        }

        if (!$year && $month && !$branchId) {
            $query->whereMonth('start_date', $month);
        }

        if ($year && $month && !$branchId) {
            $query->whereYear('start_date', $year)->whereMonth('start_date', $month);
        }


        $data = $query->paginate(self::$perPage);
        return self::formattedData($data);
    }


    private static function formattedData(LengthAwarePaginator $data): LengthAwarePaginator
    {
        $formattedData = $data->getCollection()->map(function ($item) {
            return [
                'id' => $item->id,
                'user_id' => $item->user->id,
                'user_name' => '(' . $item->user->nip . ') ' . $item->user->name,
                'start_date' => formatDate($item->start_date),
                'end_date' => formatDate($item->end_date),
                'leaves_status' => $item->leaves_status,
                'reason' => $item->reason,
                'confirmation_status' => $item->confirmation_status,
                'sick_letter' => $item->sick_letter
            ];
        });

        $data->setCollection($formattedData);

        return $data;
    }

    public function search(Request $request): LengthAwarePaginator
    {
        $search = $request->input('search');
        $query = $this->query();

        if (!empty($search)) {
            $query->whereHas('user', function ($query) use ($request, $search) {
                $query->where('name', 'like', '%' . $search . '%');
            })->orWhere('start_date', 'like', '%' . $search . '%')
                ->orWhere('reason', 'like', '%' . $search . '%')
                ->orWhere('end_date', 'like', '%' . $search . '%')
                ->orWhere('leaves_status', 'like', '%' . $search . '%')
                ->orWhere('confirmation_status', 'like', '%' . $search . '%');
        }


        $query = $this->permissionsData($request, $query);

        $data = $query->paginate(10);
        return self::formattedData($data);
    }


    public function getUserData(Request $request)
    {
        $search = $request->search;
        $query = User::with('userHasArea', 'branch', 'roles')->where('active', '=', 1)
            ->orderBy('name')
            ->select('id', 'name', 'nip');


        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('nip', 'like', '%' . $search . '%');
            });
        }

        $query = $this->permissionsUserData($request, $query);

        $users = $query->get();


        return $users->map(function ($item) {
            return [
                'id' => $item->id,
                'text' => $item->nip . ' ' . $item->name,
            ];
        });
    }



    public function permissionsUserData($request, $query){

        if ($request->user()->hasAnyRole(['NOC Supervisor', 'NOC Staff'])) {
            return $query->whereHas('roles', function ($query) {
                $query->whereIn('name', ['NOC Supervisor', 'NOC Staff']);
            })->where(function ($query) {
                $query->whereNull('branch_id')->orWhere('branch_id', 1);
            });
        }

        if ($request->user()->hasAnyRole('Super Admin', 'Operational Manager', 'FA & Tax Manager', 'Director', 'Main Commissioner')) {
            return $query;
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
                $query->whereNull('branch_id')->orWhereIn('branch_id', [1])
                    ->where('active', 1);
            });
        }


        if ($request->user()->hasAnyRole('Finance & Accounting Supervisor')) {
            return $query->whereHas('roles', function ($query) use ($request) {
                $query->whereIn('name', ['Finance & Accounting Supervisor', 'Finance & Accounting Staff', 'Tax Admin Supervisor', 'Billing Admin Supervisor', 'Customer Payment Supervisor', 'FA Senior Staff', 'Stocker Staff', 'Inventory Controller Supervisor']);
            })->where(function ($query) use ($request) {
                $query->whereHas('branch', function ($query) use ($request) {
                    $query->whereNull('branch_id')->orWhereIn('branch_id', [1])->where('active', 1);
                });
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
            return $query->whereHas('branch', function ($query) use ($request) {
                $query->where('branch_id', $request->user()->branch_id);
            });
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


    public function permissionsData(Request $request, Builder $query): Builder
    {
        if ($request->user()->hasAnyRole(['NOC Supervisor', 'NOC Staff'])) {
            return $query->whereHas('user.roles', function ($query) {
                $query->whereIn('name', ['NOC Supervisor', 'NOC Staff']);
            })->where(function ($query) {
                $query->whereNull('branch_id')->orWhere('branch_id', 1);
            });
        }

        if ($request->user()->hasAnyRole('Super Admin', 'Operational Manager', 'FA & Tax Manager', 'Director', 'Main Commissioner')) {
            return $query;
        }


        if ($request->user()->hasAnyRole(['Head Engineer', 'Senior Engineer'])) {

            return $query->whereHas('user.userHasArea', function ($query) use ($request) {
                $query->where('area_id', $request->user()->userHasArea->area_id);
            })->where(function ($query) use ($request) {
                $query->whereHas('user.branch', function ($query) use ($request) {
                    $query->where('branch_id', $request->user()->branch_id);
                })->whereHas('user', function ($query) use ($request) {
                    $query->where('active', 1);
                });
            });
        }

        if ($request->user()->hasAnyRole('Customer Service Leader')) {
            return $query->whereHas('roles', function ($query) use ($request) {
                $query->whereIn('name', ['Customer Service Leader', 'Customer Service Staff', 'After Sales Customer Service']);
            })->where(function ($query) use ($request) {
                $query->whereNull('branch_id')->orWhereIn('branch_id', [1])
                    ->where('active', 1);
            });
        }


        if ($request->user()->hasAnyRole('Finance & Accounting Supervisor')) {
            return $query->whereHas('user.roles', function ($query) use ($request) {
                $query->whereIn('name', ['Finance & Accounting Supervisor', 'Finance & Accounting Staff', 'Tax Admin Supervisor', 'Billing Admin Supervisor', 'Customer Payment Supervisor', 'FA Senior Staff', 'Stocker Staff', 'Inventory Controller Supervisor']);
            })->where(function ($query) use ($request) {
                $query->whereHas('user.branch', function ($query) use ($request) {
                    $query->whereNull('branch_id')->orWhereIn('branch_id', [1])->where('active', 1);
                });
            });
        }


        if ($request->user()->hasAnyRole('Head Of Electrical Engineer')) {
            return $query->whereHas('user.roles', function ($query) use ($request) {
                $query->whereIn('name', ['Head Of Electrical Engineer', 'Senior Electrical Engineer']);
            })->whereHas('user.branch', function ($query) use ($request) {
                $query->whereNull('branch_id');
            });
        }


        if ($request->user()->hasRole('Branch Manager')) {
            return $query->whereHas('user.branch', function ($query) use ($request) {
                $query->where('branch_id', $request->user()->branch_id);
            });
        }


        if ($request->user()->hasRole('KU Head Engineer')) {
            return $query->whereHas('user.roles', function ($query) use ($request) {
                $query->whereIn('name', ['KU Head Engineer', 'KU Engineer']);
            });
        }


        if ($request->user()->hasRole('Quality Controller Supervisor')) {
            return $query->whereHas('user.roles', function ($query) use ($request) {
                $query->whereIn('name', ['Quality Controller Supervisor', 'Quality Control Staff']);
            });
        }

        return $query;
    }

}
