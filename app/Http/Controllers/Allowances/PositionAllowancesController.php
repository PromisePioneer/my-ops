<?php

namespace App\Http\Controllers\Allowances;

use App\Http\Controllers\Controller;
use App\Http\Requests\Allowances\PositionAllowanceRequest;
use App\Models\Role;
use App\Models\RoleHasPositionAllowance;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PositionAllowancesController extends Controller
{

    public RoleHasPositionAllowance $roleHasPositionAllowances;
    private Role $role;

    public function __construct()
    {
        $this->roleHasPositionAllowances = new RoleHasPositionAllowance();
        $this->role = new Role();
    }


    public function getRoleData(Request $request): JsonResponse
    {
        return response()->json($this->role->getData($request));
    }

    public function getSelectedRole(RoleHasPositionAllowance $roleHasPositionAllowance): JsonResponse
    {
        return response()->json($this->role->selectedRole($roleHasPositionAllowance->role_id));
    }


    public function index(): View
    {
        return view('pages.payroll.allowances.position.index');
    }

    public function search(Request $request): JsonResponse
    {
        $search = $request->input('search');

        $query = $this->roleHasPositionAllowances->data();
        if (!empty($search)) {
            $query->whereHas('role', function ($query) use ($search) {
                $query->where('name', 'LIKE', "%{$search}%");
            });
        }

        $data = $query->paginate(10);
        return response()->json($data);
    }

    public function data(): JsonResponse
    {
        return response()->json($this->roleHasPositionAllowances->data()->paginate(10));
    }

    public function store(PositionAllowanceRequest $request): JsonResponse
    {
        $data = $request->validated();
        RoleHasPositionAllowance::create($data);

        return response()->json([
            'message' => 'data berhasil disimpan',
        ]);
    }

    public function edit(
        RoleHasPositionAllowance $roleHasPositionAllowance
    ): JsonResponse {
        return response()->json($roleHasPositionAllowance);
    }


    public function update(
        PositionAllowanceRequest $request,
        RoleHasPositionAllowance $roleHasPositionAllowance
    ): JsonResponse {
        $data = $request->validated();
        $roleHasPositionAllowance->update($data);
        return response()->json([
            'message' => 'data berhasil disimpan',
        ]);
    }


    public function destroy(Request $request, RoleHasPositionAllowance $roleHasPositionAllowance): JsonResponse
    {
        $implodeID = implode(',', $request->get('id'));
        $explodeID = explode(',', $implodeID);
        $roleHasPositionAllowance->whereIn('id', $explodeID)->delete();

        return response()->json([
            'message' => 'data berhasil dihapus',
        ], 200);
    }
}
