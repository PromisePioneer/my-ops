<?php

namespace App\Service\LeaveAndPermission;

use AllowDynamicProperties;
use App\Models\LeaveAndPermission;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use function App\Helper\formatDate;

#[AllowDynamicProperties] class ManageUserLeaveAndPermissionService
{
    private static int $perPage = 10;
    public function __construct()
    {
        $this->leaveAndPermission = new LeaveAndPermission();
    }


    public function data(Request $request): LengthAwarePaginator
    {
        $query = $this->leaveAndPermission->getData();
        $data = $query->paginate(10);
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
            ];
        });

        $data->setCollection($formattedData);

        return $data;
    }

    public function search(Request $request): LengthAwarePaginator
    {
        $search = $request->input('search');
        $query = $this->leaveAndPermission->getData();


        if (!empty($search)) {
            $query->WhereHas('user', function ($query) use ($request, $search) {
                $query->where('name', 'like', '%'.$search.'%');
            })->orWhere('start_date', 'like', '%'.$search.'%')
                ->orWhere('reason', 'like', '%'.$search.'%')
                ->orWhere('end_date', 'like', '%'.$search.'%')
                ->orWhere('leaves_status', 'like', '%'.$search.'%')
                ->orWhere('confirmation_status', 'like', '%'.$search.'%');
        }


        $data = $query->paginate(10);
        return self::formattedData($data);
    }


    public function getUserData(Request $request)
    {
        $search = $request->search;
        $query = User::where('active', '=', 1)
            ->orderBy('name')
            ->select('id', 'name', 'nip');

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('nip', 'like', '%' . $search . '%');
            });
        }

        if ($request->user()->hasAnyRole(['Super Admin', 'Operational Manager', 'FA & Tax Manager', 'Director', 'Main Commissioner'])) {
            $query->paginate(self::$perPage);
        }

        if ($request->user()->hasRole('NOC Supervisor')) {
            $query->whereHas('roles', function ($query) {
                $query->whereIn('name', ['NOC Supervisor', 'NOC Staff']);
            })->where(function ($query) {
                $query->whereNull('branch_id')
                    ->orWhere('branch_id', 1);
            });
        }


        if ($request->user()->hasAnyRole('Head Engineer', 'Senior Engineer')) {
            $query->whereHas('userHasArea', function ($query) use ($request) {
                $query->where('area_id', $request->user()->userHasArea->area_id);
            })->where(function ($query) use ($request) {
                $query->where('branch_id', $request->user()->branch_id)
                    ->where('active', 1);
            });
        }

        if ($request->user()->hasAnyRole('Customer Service Leader')) {
            $query->whereHas('roles', function ($query) use ($request) {
                $query->whereIn('name', ['Customer Service Leader', 'Customer Service Staff', 'After Sales Customer Service']);
            })->where(function ($query) use ($request) {
                $query->whereNull('branch_id')->orWhereIn('branch_id', [1])
                    ->where('active', 1);
            });
        }


        if ($request->user()->hasAnyRole('Finance & Accounting Supervisor')) {
            $query->whereHas('roles', function ($query) use ($request) {
                $query->whereIn('name', ['Finance & Accounting Supervisor', 'Finance & Accounting Staff', 'Tax Admin Supervisor', 'Billing Admin Supervisor', 'Customer Payment Supervisor', 'FA Senior Staff', 'Stocker Staff', 'Inventory Controller Supervisor']);
            })->where(function ($query) use ($request) {
                $query->whereNull('branch_id')->orWhereIn('branch_id', [1])->where('active', 1);;
            });
        }


        if ($request->user()->hasAnyRole('Head Of Electrical Engineer')) {
            $query->whereHas('roles', function ($query) use ($request) {
                $query->whereIn('name', ['Head Of Electrical Engineer', 'Senior Electrical Engineer']);
            })->whereNull('branch_id');
        }


        if ($request->user()->hasRole('Branch Manager')) {
            $query->where('branch_id', $request->user()->branch_id)->paginate(self::$perPage);
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

        $users = $query->get();

        return $users->map(function ($item) {
            return [
                'id' => $item->id,
                'text' => $item->nip . ' ' . $item->name,
            ];
        });
    }

}
