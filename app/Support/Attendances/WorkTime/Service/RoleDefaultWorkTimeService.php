<?php

namespace App\Support\Attendances\WorkTime\Service;

use AllowDynamicProperties;
use App\Models\RoleDefaultWorkTime;
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


        return self::formattedData($roles->paginate(self::$perPage));
    }


    private static function formattedData(LengthAwarePaginator $roles): LengthAwarePaginator
    {
        $data = $roles->getCollection()->map(function ($role) {
            $defaultWorkTime = RoleDefaultWorkTimeRepository::getDefaultWorkTime($role->id) ?? WorkTimeRepository::getDefaultWorkTime();
            return [
                'id' => $role->id,
                'name' => $role->name,
                'work_time' => WorkTimeService::getWorkTime($role->defaultWorkTime?->workTime),
                'default_work_time' => WorkTimeService::getWorkTime($defaultWorkTime),
                'work_time_id' => $role->defaultWorkTime?->workTime?->id,
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
