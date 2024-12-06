<?php

namespace App\Http\Controllers\Master\General;

use AllowDynamicProperties;
use App\Http\Controllers\Controller;
use App\Http\Requests\Master\Role\RoleRequest;
use App\Models\Department;
use App\Models\Role;
use App\Models\RoleHasDepartment;
use App\Service\User\RoleService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;
use Throwable;

#[AllowDynamicProperties] class RoleController extends Controller
{
    public readonly int $perPage;

    public function __construct()
    {
        $this->roleService = new RoleService();
        $this->departments = new Department();
    }

    /**
     * @throws AuthorizationException
     */
    public function index(): View
    {
        $this->authorize('view', Role::class);
        return view('pages.general-master-data.role.index');
    }

    public function rolesData(): JsonResponse
    {
        $this->authorize('view', Role::class);
        return response()->json($this->roleService->rolePermissionAndDepartmentsPaginatedData());
    }


    public function search(Request $request): JsonResponse
    {
        $this->authorize('view', Role::class);
        return response()->json($this->roleService->searchRole($request));
    }

    public function create(): View
    {
        $this->authorize('create', Role::class);
        $permissions = Permission::get(['id', 'name']);
        return view('pages.general-master-data.role.create', compact('permissions'));
    }


    public function getDepartments(Request $request): JsonResponse
    {
        $this->authorize('create', Role::class);
        return response()->json($this->departments->getData($request));
    }

    public function store(RoleRequest $request): JsonResponse
    {
        $this->authorize('create', Role::class);
        DB::transaction(function () use ($request) {
            $role = Role::create(['name' => $request->input('name')]);
            RoleHasDepartment::create([
                'role_id' => $role->id,
                'department_id' => $request->department_id,
            ]);
            $role->givePermissionTo($request->permission);
        });

        return response()->json([
            'message' => 'data sukses disimpan!',
        ]);
    }


    public function getSelectedDepartment(Role $role): JsonResponse
    {
        $this->authorize('update', $role);
        $data = Role::with('department')->where('id', $role->id)->first();
        return response()->json($this->departments->getSelectedData($data->department->first()->id));
    }


    public function edit(Role $role): View
    {
        $this->authorize('update', $role);
        $permissions = Permission::all();
        $roleHasPermissions = $role->permissions()->pluck('name')->toArray();
        return view('pages.general-master-data.role.edit', compact('role', 'permissions', 'roleHasPermissions'));
    }

    /**
     * @throws Throwable
     */


    public function show(Role $role): JsonResponse
    {
        $this->authorize('update', $role);
        $associatedPermissions = DB::table('role_has_permissions')->where('role_has_permissions.role_id', $role->id)
            ->pluck('role_has_permissions.permission_id', 'role_has_permissions.permission_id')
            ->all();

        return response()->json($associatedPermissions);
    }

    /**
     * @throws AuthorizationException
     */
    public function associatedUsers(Role $role): JsonResponse
    {
        $this->authorize('update', $role);
        $users = $this->roleService->associatedUsers($role->id);
        return response()->json([
            'data' => $users,
            'total_user' => $users->count(),
        ]);
    }

    public function update(Role $role, RoleRequest $request): JsonResponse
    {
        $this->authorize('update', $role);
        DB::transaction(function () use ($request, $role) {
            $role->update([
                'name' => $request->input('name'),
            ]);
            RoleHasDepartment::updateOrCreate(
                ['role_id' => $role->id],
                ['department_id' => $request->department_id]
            );
            $role->syncPermissions($request->input('permission'));
        });


        return response()->json([
            'message' => 'data sukses diupdate!',
            'data' => $role,
        ]);
    }

    public function destroy(Role $role): JsonResponse
    {
        $this->authorize('delete', $role);
        $role->delete();
        return response()->json([
            'message' => 'data sukses dihapus!',
            'data' => $role,
        ]);
    }
}
