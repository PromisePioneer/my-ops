<?php

namespace App\Service\LeaveAndPermission;

use App\Models\LeaveAndPermission;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

use function App\Helper\formatDate;

class ManageUserLeaveAndPermissionService
{
    private LeaveAndPermission $leaveAndPermission;

    public function __construct()
    {
        $this->leaveAndPermission = new LeaveAndPermission();
    }


    public function data(Request $request): LengthAwarePaginator
    {
        $query = $this->leaveAndPermission->getData();
        self::isBranchManagerRoleFilterQuery($request, $query);
        $data = $query->paginate(10);
        return self::formattedData($data);
    }

    private static function isBranchManagerRoleFilterQuery(Request $request, $query, string|null $search = null): void
    {
        if ($request->user()->hasRole('Branch Manager')) {
            $query->whereHas('user', function ($query) use ($search, $request) {
                $query->where('branch_id', $request->user()->branch_id);
            });
        }
    }

    private static function formattedData(LengthAwarePaginator $data): LengthAwarePaginator
    {
        $formattedData = $data->getCollection()->map(function ($item) {
            return [
                'id' => $item->id,
                'user_id' => $item->user->id,
                'user_name' => $item->user->name,
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

        self::isBranchManagerRoleFilterQuery($request, $query, $search);

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


    public function filter($branchId = null): LengthAwarePaginator
    {
        $query = $this->leaveAndPermission->getData();
    }
}