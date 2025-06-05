<?php

namespace App\Support\Attendances\WorkTime\Service;

use AllowDynamicProperties;
use App\Models\RoleDefaultWorkTime;
use App\Models\WorkTime;
use App\Support\Attendances\WorkTime\Repositories\RoleDefaultWorkTimeRepository;
use App\Support\Attendances\WorkTime\Repositories\WorkTimeRepository;
use App\Support\User\Role\Repository\RoleRepository;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

#[AllowDynamicProperties] class RoleDefaultWorkTimeService
{

    private static int $perPage = 10;

    public function __construct()
    {
        $this->roleDefaultWorkTimeRepository = new RoleDefaultWorkTimeRepository();
        $this->roleRepository = new RoleRepository();
    }


    public function data(): LengthAwarePaginator
    {
        $roles = $this->roleRepository->getRoleWithWorkTime()->paginate(self::$perPage);
        return self::formattedData($roles);
    }


    public function search(Request $request): LengthAwarePaginator
    {
        $search = $request->input('search');
        $roles = $this->roleRepository->getRoleWithWorkTime();
        if (!empty($search)) {
            $roles = $this->roleDefaultWorkTimeRepository->searchQuery($roles, $search);
        }

        $roles->paginate(self::$perPage);
        return self::formattedData($roles);
    }


    private static function formattedData(LengthAwarePaginator $roles): LengthAwarePaginator
    {
        $data = $roles->map(function ($role) {
            $defaultWorkTime = WorkTimeRepository::getDefaultWorkTime();
            return [
                'id' => $role->id,
                'name' => $role->name,
                'work_time' => WorkTimeService::getWorkTime($role->defaultWorkTime?->workTime ?? $defaultWorkTime),
                'work_time_id' => $role->defaultWorkTime->workTime?->id ?? $defaultWorkTime->id,
            ];
        });

        $roles->setCollection($data);
        return $roles;
    }


    public function store(Request $request): void
    {
        RoleDefaultWorkTime::updateOrCreate([
            'role_id' => $request->role_id,
        ], [
            'work_time_id' => $request->work_time_id
        ]);
    }
}
