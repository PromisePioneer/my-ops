<?php

namespace App\Support\User\Role\Service;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\Permission\Models\Permission;

class RoleService
{
    private static int $perPage = 10;
    private Role $role;


    public function __construct()
    {
        $this->role = new Role();
    }


    public function rolePermissionAndDepartmentsPaginatedData(): LengthAwarePaginator
    {
        $data = $this->role->rolePermissionAndDepartments()->paginate(self::$perPage);
        self::formattedData($data);

        return $data;
    }

    public function formattedData(LengthAwarePaginator $data): void
    {
        $roleData = $data->getCollection()->map(function ($item) {
            $permission = Permission::whereIn('name', $item->permissions->pluck('name'))->take(5);
            $totalUser = User::role($item->name)->count();

            return [
                'id' => $item->id,
                'role_name' => $item->name,
                'department' => $item->department[0]->name ?? null,
                'permissions' => $permission->get(),
                'total_permission_in_this_role' => $item->permissions->count() - 5,
                'total_user' => $totalUser,
            ];
        });

        $data->setCollection($roleData);
    }

    public function searchRole(Request $request): LengthAwarePaginator
    {
        $search = $request->input('search');
        $query = Role::search($search)->query(callback: function ($query) {
            $query->with('permissions', 'department');
        })->paginate(self::$perPage);

        self::formattedData($query);
        return $query;
    }


    public function rolesData(Request $request): array
    {
        $search = $request->input('search');
        $roles = Role::search($search)->query(callback: function ($query) {
            $query->select('id', 'name')->orderBy('name');
        })->get();


        return $roles->map(function ($item) {
            return [
                'id' => $item->id,
                'text' => $item->nip . ' ' . $item->name,
            ];
        })->toArray();
    }


    public function selectedRole(Role $role): array
    {
        return [
            'id' => $role->id,
            'name' => $role->name,
        ];
    }
}
