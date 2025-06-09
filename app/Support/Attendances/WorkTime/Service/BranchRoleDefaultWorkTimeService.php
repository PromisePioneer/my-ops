<?php

namespace App\Support\Attendances\WorkTime\Service;

use AllowDynamicProperties;
use App\Http\Requests\BranchRoleDefaultWorkTimeRequest;
use App\Models\BranchRoleDefaultWorkTime;
use App\Models\Master\Common\Branch;
use App\Support\Attendances\WorkTime\Repositories\BranchDefaultWorkTimeRepository;
use App\Support\Attendances\WorkTime\Repositories\RoleDefaultWorkTimeRepository;
use App\Support\User\Role\Repository\RoleRepository;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

#[AllowDynamicProperties] class BranchRoleDefaultWorkTimeService
{

    private static int $perPage = 10;

    public function __construct()
    {
        $this->roleRepository = new RoleRepository();
    }

    public function data(Branch $branch): LengthAwarePaginator
    {
        $roles = $this->roleRepository->getBranchRoles($branch)->paginate(self::$perPage);
        return self::formattedData($roles, $branch);
    }


    public function search(Request $request, Branch $branch): LengthAwarePaginator
    {
        $search = $request->input('search');
        $query = $this->roleRepository->getBranchRoles($branch)
            ->orderBy('name');

        if (!empty($search)) {
            $query->where(function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            });
        }

        $data = $query->paginate(self::$perPage);
        return self::formattedData($data, $branch);
    }


    public function formattedData(LengthAwarePaginator $branchRoles, Branch $branch): LengthAwarePaginator
    {
        $data = $branchRoles->getCollection()->map(function ($branchRole) use ($branch) {
            return [
                'id' => $branchRole->id,
                'name' => $branchRole->name,
                'work_time_id' => BranchDefaultWorkTimeRepository::getDefaultWorkTime($branch),
                'branch_id' => $branchRole->branchRoleDefaultWorkTime?->branch_id ?? $branch->id,
                'actual_work_time_id' => $branchRole->branchRoleDefaultWorkTime?->work_time_id,
                'branch_role_default_work_time' => WorkTimeService::getWorkTime($branchRole->branchRoleDefaultWorkTime?->workTime),
                'role_default_work_time' => WorkTimeService::getWorkTime(RoleDefaultWorkTimeRepository::getDefaultWorkTime($branchRole->id)),
            ];
        });

        $branchRoles->setCollection($data);
        return $branchRoles;
    }


    public function store(Branch $branch, BranchRoleDefaultWorkTimeRequest $request): BranchRoleDefaultWorkTime
    {
        return BranchRoleDefaultWorkTime::updateOrCreate([
            'branch_id' => $branch->id,
            'role_id' => $request->role_id,
        ], [
            'work_time_id' => $request->work_time_id,
        ]);
    }
}
