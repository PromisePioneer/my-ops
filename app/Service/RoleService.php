<?php

namespace App\Service;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleService
{

    public function getRoleWithPermissionAndPagination(int $perPage)
    {
        $roles = Role::with('permissions')->paginate($perPage);
        self::formattedData($roles);

        return $roles;
    }

    public function formattedData(LengthAwarePaginator $roles): void
    {
        $roleData = $roles->getCollection()->map(function ($item) {
            $permission = Permission::whereIn('name', $item->permissions->pluck('name'))->take(5);
            $totalUser = User::role($item->name)->count();

            return [
                'id' => $item->id,
                'role_name' => $item->name,
                'permissions' => $permission->get(),
                'total_permission_in_this_role' => $item->permissions->count() - 5,
                'total_user' => $totalUser,
            ];
        });

        $roles->setCollection($roleData);
    }

    public function searchRole(Request $request, int $perPage): LengthAwarePaginator
    {
        $query = Role::with('permissions')
            ->where('name', 'like', '%'.$request->search.'%')
            ->paginate($perPage);
        self::formattedData($query);

        return $query;
    }


}