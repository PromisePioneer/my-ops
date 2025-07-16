<?php

namespace App\Http\Controllers\HRIS\RolePermissions;

use App\Http\Controllers\Controller;
use App\Http\Requests\Permission\PermissionRequest;
use App\Models\Permission;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PermissionController extends Controller
{
    public static int $perPage = 10;
    /**
     * @throws AuthorizationException
     */
    public function index(): View
    {
        $this->authorize('view', Permission::class);
        return view('pages.manage-users.permission.index');
    }

    /**
     * @throws AuthorizationException
     */
    public function permissionData(): JsonResponse
    {
        $this->authorize('view', Permission::class);
        $permission = Permission::paginate(self::$perPage)->onEachSide(1);
        return response()->json($permission);
    }

    /**
     * @throws AuthorizationException
     */
    public function search(Request $request): JsonResponse
    {
        $this->authorize('view', Permission::class);
        $search = $request->input('search');
        $query = Permission::search($search)->paginate(self::$perPage);

        return response()->json($query);
    }

    /**
     * @throws AuthorizationException
     */
    public function store(PermissionRequest $request): JsonResponse
    {
        $this->authorize('create', Permission::class);
        $permission = Permission::create($request->validated());
        return response()->json([
            'message' => 'data sukses disimpan!',
            'data' => $permission,
        ]);
    }

    /**
     * @throws AuthorizationException
     */
    public function create(): View
    {
        $this->authorize('create', Permission::class);
        return view('pages.manage-users.permission.create');
    }

    /**
     * @throws AuthorizationException
     */
    public function show(Permission $permission): JsonResponse
    {
        $this->authorize('update', $permission);
        return response()->json($permission);
    }

    /**
     * @throws AuthorizationException
     */
    public function update(Permission $permission, PermissionRequest $request): JsonResponse
    {
        $this->authorize('update', $permission);
        $permission->update($request->validated());

        return response()->json([
            'message' => 'data sukses diupdate!',
            'data' => $permission,
        ]);
    }

    /**
     * @throws AuthorizationException
     */
    public function destroy(Request $request, Permission $permission): JsonResponse
    {
        $this->authorize('delete', $permission);
        $implodeID = implode(',', $request->get('id'));
        $explodeID = explode(',', $implodeID);
        $permission->whereIn('id', $explodeID)->delete();
        return response()->json(['message' => 'data sukses dihapus!']);
    }
}
