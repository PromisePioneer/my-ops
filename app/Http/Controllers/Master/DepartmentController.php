<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\Department\DepartmentRequest;
use App\Models\Department;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    private static int $perPage = 10;

    public function index()
    {
        return view('pages.master.department.index');
    }

    public function data(): JsonResponse
    {
        $departments = Department::paginate(self::$perPage);
        return response()->json($departments);
    }

    public function search(Request $request): JsonResponse
    {
        $search = $request->input('search');
        $departments = Department::where('code', 'like', '%' . $search . '%')
            ->orWhere('name', 'like', '%' . $search . '%')
            ->get();

        return response()->json($departments);
    }


    public function store(DepartmentRequest $request): JsonResponse
    {
        Department::create($request->validated());

        return response()->json([
            'message' => 'Data sukses disimpan'
        ]);
    }

    public function edit(Department $department): JsonResponse
    {
        return response()->json($department);
    }

    public function update(Department $department, DepartmentRequest $request): JsonResponse
    {
        $department->update($request->validated());

        return response()->json([
            'message' => 'data berhasil diubah'
        ]);
    }

    public function destroy(Department $department): JsonResponse
    {
        $department->delete();

        return response()->json([
            'message' => 'data berhasil dihapus'
        ]);
    }
}
