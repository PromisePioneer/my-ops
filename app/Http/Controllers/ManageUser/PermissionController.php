<?php

namespace App\Http\Controllers\ManageUser;

use App\Http\Controllers\Controller;
use App\Http\Requests\Permission\PermissionRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public int $perPage = 10;

    public function __construct()
    {
        $this->middleware('permission:lihat permission', ['only' => ['index']]);
        $this->middleware('permission:tambah permission', ['only' => ['create', 'store']]);
        $this->middleware('permission:update permission', ['only' => ['edit', 'update']]);
        $this->middleware('permission:hapus permission', ['only' => ['destroy']]);
    }

    public function index(): View
    {
        return view('pages.manage-users.permission.index');
    }

    public function permissionData(): JsonResponse
    {
        $permission = Permission::paginate($this->perPage);

        return response()->json($permission);
    }

    public function search(Request $request): JsonResponse
    {
        $search = $request->input('search');
        $query = Permission::where('name', 'like', '%'.$search.'%')->get();

        return response()->json($query);
    }

    public function create(): View
    {
        return view('pages.manage-users.permission.create');
    }

    public function store(PermissionRequest $request): JsonResponse
    {
        $permission = Permission::create($request->validated());

        return response()->json([
            'message' => 'data sukses disimpan!',
            'data' => $permission,
        ]);
    }

    public function show(Permission $permission): JsonResponse
    {
        return response()->json($permission);
    }

    public function update(Permission $permission, PermissionRequest $request): JsonResponse
    {
        $permission->update($request->validated());

        return response()->json([
            'message' => 'data sukses diupdate!',
            'data' => $permission,
        ]);
    }

    public function destroy(Permission $permission): JsonResponse
    {
        $permission->delete();

        return response()->json([
            'message' => 'data sukses dihapus!',
            'data' => $permission,
        ]);
    }
}
