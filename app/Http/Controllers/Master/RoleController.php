<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\Role\RoleRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{

    private static int $perPage = 10;

    public function __construct()
    {
        $this->middleware('permission:lihat role', ['only' => ['index']]);
        $this->middleware('permission:tambah role', ['only' => ['create', 'store']]);
        $this->middleware('permission:update role', ['only' => ['edit', 'update']]);
        $this->middleware('permission:hapus role', ['only' => ['destroy']]);
    }

    public function index(): View
    {
        return view('pages.master.role.index');
    }

    public function rolesData(): JsonResponse
    {
        $roles = Role::paginate(self::$perPage);
        return response()->json($roles);
    }

    public function create(): View
    {
        return view('pages.master.role.create');
    }


    public function getPermission(): JsonResponse
    {
        $permission = Permission::paginate(self::$perPage);
        return response()->json($permission);
    }


    public function searchRole(Request $request): JsonResponse
    {
        $query = Role::where('name', 'like', '%' . $request->search . '%')->get();
        return response()->json($query);
    }


    public function edit(Role $role): View
    {
        return view('pages.master.role.edit', compact('role'));
    }

    public function search(Request $request): JsonResponse
    {
        $search = $request->input('search');
        $query = Role::where('name', 'like', '%' . $search . '%')->get();
        return response()->json($query);
    }

    public function store(RoleRequest $request): JsonResponse
    {
        $role = Role::create(['name' => $request->input('name')]);
        $role->givePermissionTo($request->permission);

        return response()->json([
            'message' => "data sukses disimpan!",
            'data' => $role,
        ]);
    }

    public function show(Role $role): JsonResponse
    {
        $associatedPermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id", $role->id)
            ->pluck('role_has_permissions.permission_id', 'role_has_permissions.permission_id')
            ->all();

        return response()->json($associatedPermissions);
    }


    public function update(Role $role, RoleRequest $request): JsonResponse
    {

        $role->update([
            'name' => $request->input('name')
        ]);
        $role->syncPermissions($request->input('permission'));

        return response()->json([
            'message' => "data sukses diupdate!",
            'data' => $role,
        ]);
    }


    public function destroy(Role $role): JsonResponse
    {
        $role->delete();
        return response()->json([
            'message' => "data sukses dihapus!",
            'data' => $role,
        ]);
    }
}
