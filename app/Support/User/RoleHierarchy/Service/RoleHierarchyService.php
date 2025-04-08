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

            $user = User::with('roles')->whereHas('roles', function ($query) use ($roleHierarchy) {
                $query->where('id', $roleHierarchy->role_id);
            })->first();


            return [
                'id' => $roleHierarchy->id,
                'user_name' => $user?->name,
                'role_name' => $roleHierarchy->role->name,
                'img' => url('assets/media/avatars/blank.png'),
                'pid' => $roleHierarchy->parent_id,
            ];
        });
    }


    public function search()
    {

    }
}
