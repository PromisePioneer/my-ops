<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\Role\RoleRequest;
use App\Models\User;
use App\Service\RoleService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public readonly int $perPage;
    private RoleService $roleService;

    public function __construct()
    {
        $this->roleService = new RoleService();
        $this->perPage = 5;
    }

    /**
     * @throws AuthorizationException
     */
    public function index(): View
    {
        $this->authorize('view', Role::class);

        return view('pages.master.role.index');
    }

    /**
     * @throws AuthorizationException
     */
    public function rolesData(): JsonResponse
    {
        $this->authorize('view', Role::class);
        return response()->json($this->roleService->getRoleWithPermissionAndPagination($this->perPage));
    }


    /**
     * @throws AuthorizationException
     */
    public function search(Request $request): JsonResponse
    {
        return response()->json($this->roleService->searchRole($request, $this->perPage));
    }

    /**
     * @throws AuthorizationException
     */
    public function getPermission(): JsonResponse
    {
        $this->authorize('create', Role::class);
        $permission = Permission::all();

        return response()->json($permission);
    }

    /**
     * @throws AuthorizationException
     */
    public function edit(Role $role): JsonResponse
    {
        $rolesData = Role::with('permissions')->where('id', $role->id)->first();
        return response()->json($rolesData);
    }


    /**
     * @throws AuthorizationException
     */
    public function store(RoleRequest $request): JsonResponse
    {
        $this->authorize('create', Role::class);
        $role = Role::create(['name' => $request->input('name')]);
        $role->givePermissionTo($request->permission);

        return response()->json([
            'message' => 'data sukses disimpan!',
            'data' => $role,
        ]);
    }

    /**
     * @throws AuthorizationException
     */
    public function create(): View
    {
        $this->authorize('create', Role::class);
        return view('pages.master.role.create');
    }

    /**
     * @throws AuthorizationException
     */
    public function show(Role $role): JsonResponse
    {
        $this->authorize('update', Role::class);
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
        $users = User::with('branch')
            ->whereHas('roles', function ($query) use ($role) {
                $query->where('id', $role->id);
            })->paginate($this->perPage);

        return response()->json([
            'data' => $users,
            'total_user' => $users->count()
        ]);
    }

    /**
     * @throws AuthorizationException
     */
    public function update(Role $role, RoleRequest $request): JsonResponse
    {
        $this->authorize('update', Role::class);
        $role->update([
            'name' => $request->input('name'),
        ]);
        $role->syncPermissions($request->input('permission'));

        return response()->json([
            'message' => 'data sukses diupdate!',
            'data' => $role,
        ]);
    }

    /**
     * @throws AuthorizationException
     */
    public function destroy(Role $role): JsonResponse
    {
        $this->authorize('delete', Role::class);
        $role->delete();
        return response()->json([
            'message' => 'data sukses dihapus!',
            'data' => $role,
        ]);
    }
}
