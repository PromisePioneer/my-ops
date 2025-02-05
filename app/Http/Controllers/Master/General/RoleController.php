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

    /**
     * @throws AuthorizationException
     */
    public function rolesData(): JsonResponse
    {
        $this->authorize('view', Role::class);
        return response()->json($this->roleService->rolePermissionAndDepartmentsPaginatedData());
    }


    /**
     * @throws AuthorizationException
     */
    public function search(Request $request): JsonResponse
    {
        $this->authorize('view', Role::class);
        return response()->json($this->roleService->searchRole($request));
    }

    /**
     * @throws AuthorizationException
     */
    public function create(): View
    {
        $this->authorize('create', Role::class);
        return view('pages.general-master-data.role.create');
    }


    /**
     * @throws AuthorizationException
     */
    public function getDepartments(Request $request): JsonResponse
    {
        $this->authorize('create', Role::class);
        return response()->json($this->departments->getData($request));
    }

    public function getPermissions(): JsonResponse
    {
        return response()->json(Permission::all());
    }

    public function searchPermission(Request $request): JsonResponse
    {
        $permission = Permission::where('name', 'like', '%' . $request->search . '%')->get();
        return response()->json($permission);
    }

    /**
     * @throws Throwable
     * @throws AuthorizationException
     */
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


    /**
     * @throws AuthorizationException
     */
    public function getSelectedDepartment(Role $role): JsonResponse
    {
        $this->authorize('update', $role);
        $data = Role::with('department')->where('id', $role->id)->first();
        return response()->json($this->departments->getSelectedData($data->department->first()->id));
    }

    public function getSelectedPermission(Role $role): JsonResponse
    {
        return response()->json($role->permissions()->pluck('name')->toArray());
    }


    /**
     * @throws AuthorizationException
     */
    public function edit(Role $role): View
    {
        $this->authorize('update', $role);
        return view('pages.general-master-data.role.edit', compact('role'));
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
     * @throws Throwable
     */
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
