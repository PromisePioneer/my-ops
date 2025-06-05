<?php

namespace App\Support\Attendances\WorkTime\Service;

use AllowDynamicProperties;
use App\Models\Master\Common\Branch;
use App\Support\User\Role\Repository\RoleRepository;
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
        return self::formattedData($roles);
    }


    public function formattedData(LengthAwarePaginator $branchRoles): LengthAwarePaginator
    {
        $data = $branchRoles->getCollection()->map(function ($branchRole) {
            return [
                'id' => $branchRole->id,
                'name' => $branchRole->name,
            ];
        });

        $branchRoles->setCollection($data);
        return $branchRoles;
    }
}
