<?php

namespace App\Http\Controllers\Master\General;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\Role\RoleRequest;
use App\Models\Department;
use App\Models\Role;
use App\Models\RoleHasDepartment;
use App\Service\User\RoleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;
use Throwable;

class RoleController extends Controller
{
    public readonly int $perPage;

    private RoleService $roleService;
    private Department $departments;

    public function __construct()
    {
        $this->roleService = new RoleService();
        $this->departments = new Department();
    }

    public function index(): View
    {
        return view('pages.general-master-data.role.index');
    }

    public function rolesData(): JsonResponse
    {
        return response()->json($this->roleService->rolePermissionAndDepartmentsPaginatedData());
    }


    public function search(Request $request): JsonResponse
    {
        return response()->json($this->roleService->searchRole($request));
    }

    public function create(): View
    {
        $permissions = Permission::get(['id', 'name']);

        return view('pages.general-master-data.role.create', compact('permissions'));
    }


    public function getDepartments(Request $request): JsonResponse
    {
        return response()->json($this->departments->getData($request));
    }


    public function getSelectedDepartment(Role $role): JsonResponse
    {
        $data = Role::with('department')->where('id', $role->id)->first();
        return response()->json($this->departments->getSelectedData($data->department->first()->id));
    }


    public function edit(Role $role): View
    {
        $permissions = Permission::all();

        $roleHasPermissions = $role->permissions()->pluck('name')->toArray();

        return view('pages.general-master-data.role.edit', compact('role', 'permissions', 'roleHasPermissions'));
    }

    /**
     * @throws Throwable
     */
    public function store(RoleRequest $request): JsonResponse
    {

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

    public function show(Role $role): JsonResponse
    {
        $associatedPermissions = DB::table('role_has_permissions')->where('role_has_permissions.role_id', $role->id)
            ->pluck('role_has_permissions.permission_id', 'role_has_permissions.permission_id')
            ->all();

        return response()->json($associatedPermissions);
    }

    public function detail(Role $role): View
    {
        return view('pages.master.role.detail', compact('role'));
    }

    public function associatedUsers(Role $role): JsonResponse
    {
        $users = $this->roleService->associatedUsers($role->id);
        return response()->json([
            'data' => $users,
            'total_user' => $users->count(),
        ]);
    }

    public function update(Role $role, RoleRequest $request): JsonResponse
    {
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
        $role->delete();
        return response()->json([
            'message' => 'data sukses dihapus!',
            'data' => $role,
        ]);
    }
}
