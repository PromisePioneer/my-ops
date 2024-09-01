<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\Department\DepartmentRequest;
use App\Models\Department;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DepartmentController extends Controller
{
    private static int $perPage = 10;

    /**
     * @throws AuthorizationException
     */
    public function index(): View
    {
        $this->authorize('view', Department::class);

        return view('pages.master.department.index');
    }

    /**
     * @throws AuthorizationException
     */
    public function data(): JsonResponse
    {
        $this->authorize('view', Department::class);
        $departments = Department::paginate(self::$perPage);

        return response()->json($departments);
    }

    /**
     * @throws AuthorizationException
     */
    public function search(Request $request): JsonResponse
    {
        $this->authorize('view', Department::class);
        $search = $request->input('search');
        $departments = Department::where('code', 'like', '%'.$search.'%')
            ->orWhere('name', 'like', '%'.$search.'%')
            ->get();

        return response()->json($departments);
    }

    /**
     * @throws AuthorizationException
     */
    public function store(DepartmentRequest $request): JsonResponse
    {
        $this->authorize('create', Department::class);
        Department::create($request->validated());

        return response()->json([
            'message' => 'Data sukses disimpan',
        ]);
    }

    /**
     * @throws AuthorizationException
     */
    public function edit(Department $department): JsonResponse
    {
        $this->authorize('update', $department);

        return response()->json($department);
    }

    /**
     * @throws AuthorizationException
     */
    public function update(Department $department, DepartmentRequest $request): JsonResponse
    {
        $this->authorize('update', $department);
        $department->update($request->validated());

        return response()->json([
            'message' => 'data berhasil diubah',
        ]);
    }

    /**
     * @throws AuthorizationException
     */
    public function destroy(Department $department): JsonResponse
    {
        $this->authorize('delete', $department);
        $department->delete();

        return response()->json([
            'message' => 'data berhasil dihapus',
        ]);
    }
}
