<?php

namespace App\Http\Controllers\Master\Common;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\Common\Department\DepartmentRequest;
use App\Models\Department;
use App\Support\Master\Common\Department\DepartmentQueryFilter;
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
        return view('pages.master.common.departments.index');
    }

    /**
     * @throws AuthorizationException
     */
    public function data(): JsonResponse
    {
        $this->authorize('view', Department::class);
        $departments = Department::orderBy('code')->paginate(self::$perPage);

        return response()->json($departments);
    }

    /**
     * @throws AuthorizationException
     */
    public function search(Request $request): JsonResponse
    {
        $this->authorize('view', Department::class);
        $search = $request->input('search');
        $departments = Department::search($search)
            ->query(fn($query) => $query->orderBy('code'))
            ->paginate(self::$perPage);

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
    public function destroy(Request $request, Department $department): JsonResponse
    {
        $this->authorize('delete', $department);
        $implodeID = implode(',', $request->get('id'));
        $explodeID = explode(',', $implodeID);
        $department->whereIn('id', $explodeID)->delete();

        return response()->json([
            'message' => 'data berhasil dihapus',
        ]);
    }

    public function getDepartments(Request $request): array
    {
        $search = $request->input('search');
        $departments = Department::search($search)->query(callback: function ($query) use ($request) {
            $query = $query->orderby('name', 'asc');
            DepartmentQueryFilter::apply($query, $request);
        })->get();

        return $departments->map(function ($item) {
            return [
                'id' => $item->id,
                'text' => $item->name,
            ];
        })->toArray();
    }

    public function selectedDepartment(?int $departmentId): array
    {
        $department = Department::where('id', $departmentId)->first();

        return [
            'id' => $department?->id ?? '-',
            'name' => $department?->name ?? '-',
        ];
    }
}
