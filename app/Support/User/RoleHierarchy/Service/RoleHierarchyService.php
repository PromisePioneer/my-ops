<?php

namespace App\Support\User\RoleHierarchy\Service;

use App\Models\RoleHierarchy;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class RoleHierarchyService
{
    private static int $perPage = 10;

    public function data(): Collection
    {
        $data = RoleHierarchy::with('role', 'children')->get();
        return self::formattedData($data);
    }


    private static function formattedData($data)
    {
        return $data->map(function ($roleHierarchy) {
            return [
                'id' => $roleHierarchy->id,
                'role_name' => $roleHierarchy->role->name,
                'pid' => $roleHierarchy->parent_id,
            ];
        });
    }


    public function search()
    {

    }
}
