<?php

namespace App\Support\User\LeaveAndPermission;

use AllowDynamicProperties;
use App\Models\LeaveAndPermission;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use function App\Helper\formatDate;

#[AllowDynamicProperties] class LeaveAndPermissionService
{
    private static int $perPage = 10;

    public function __construct()
    {
        $this->leaveAndPermission = new LeaveAndPermission();
        $this->leaveRepository = new LeaveRepository();
    }

    public function data(Request $request): LengthAwarePaginator
    {
        $query = $this->leaveRepository->leavesMainQuery();
        $permissions = LeaveACLFilter::apply($query, $request);
        $query = $permissions->paginate(self::$perPage);
        return self::formattedData($query);
    }


    public function filter(Request $request): LengthAwarePaginator
    {
        $query = $this->leaveRepository->leavesMainQuery();
        $filter = LeaveQueryFilter::apply($query, $request);
        $permissions = LeaveACLFilter::apply($filter, $request);
        $data = $permissions->paginate(self::$perPage);
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
                'sick_letter' => $item->sick_letter,
                'created_at' => formatDate($item->created_at),
            ];
        });

        $data->setCollection($formattedData);

        return $data;
    }

    public function search(Request $request): LengthAwarePaginator
    {
        $search = $request->input('search', '');

        $query = LeaveAndPermission::search($search)->query(function ($query) {
            $query->join('users', 'users.id', '=', 'leaves_and_permissions.user_id')
                ->select('leaves_and_permissions.*', 'users.nip', 'users.name');
        });

        $leaveACLFilter = LeaveACLFilter::apply($query, $request);

        return self::formattedData($leaveACLFilter->paginate(self::$perPage));
    }


    public function getUserData(Request $request)
    {
        $search = $request->input('search');
        $users = User::search($search)->query(function ($query) use ($request) {
            $newQuery = $query->where('active', true);
            LeaveSelect2QueryFilter::apply($newQuery, $request);
        })->get();

        return $users->map(function ($item) {
            return [
                'id' => $item->id,
                'text' => $item->nip . ' ' . $item->name,
            ];
        });
    }

    public function getOwnleaves(Request $request): LengthAwarePaginator
    {
        $leaves = LeaveAndPermission::with('accBy', 'user', 'user.userHasArea', 'user.branch', 'user.roles.department')
            ->where('user_id', $request->user()->id)
            ->orderBy('created_at')
            ->paginate(self::$perPage);

        return self::formattedData($leaves);
    }

}
