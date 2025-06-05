<?php

namespace App\Support\Attendances\WorkTime\Service;

use AllowDynamicProperties;
use App\Models\Master\Common\Branch;
use App\Support\User\Role\Repository\RoleRepository;
use Illuminate\Pagination\LengthAwarePaginator;

#[AllowDynamicProperties] class BranchAndRoleDefaultWorkTimeService
{

    private static int $perPage = 10;

    public function __construct()
    {
        $this->roleRepository = new RoleRepository();
    }

    public function data(Branch $branch)
    {
        $roles = $this->roleRepository->getBranchRoles($branch)->paginate(self::$perPage);
    }


    public function formattedData(LengthAwarePaginator $branchRoles)
    {
        $data = $branchRoles->getCollection()->map(function ($branchRole) {
            return [
                'id' => $branchRole->id,
                'name' => $branchRole->name,
                'start_time' => $branchRole->defaultWorkTime->start_time,
                'end_time' => $branchRole->defaultWorkTime->end_time,
            ];
        });
    }
}
