<?php

namespace App\Http\Controllers\Allowances;

use App\Http\Controllers\Controller;
use App\Http\Requests\Allowances\MealAllowanceRequest;
use App\Models\Role;
use App\Models\RoleHasMealAllowance;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MealAllowanceController extends Controller
{

    private RoleHasMealAllowance $roleHasMealAllowance;
    private Role $role;

    public function __construct()
    {
        $this->roleHasMealAllowance = new RoleHasMealAllowance();
        $this->role = new Role();
    }


    public function getRoleData(Request $request): JsonResponse
    {
        return response()->json($this->role->getData($request));
    }

    public function selectedRoleData(RoleHasMealAllowance $roleHasMealAllowance): JsonResponse
    {
        return response()->json($roleHasMealAllowance);
    }

    public function index(): View
    {
        return view('pages.payroll.allowances.meal.index');
    }

    public function search(Request $request): JsonResponse
    {
        $search = $request->input('search');
        $query = $this->roleHasMealAllowance->data();


        if (!empty($search)) {
            $query->whereHas('role', function ($query) use ($search) {
                $query->where('name', 'like', "%".$search."%");
            })->where('name', 'like', "%".$search."%")
                ->orWhere('amount', 'like', "%".$search."%");
        }

        $data = $query->paginate(10);
        return response()->json($data);
    }

    public function data(): JsonResponse
    {
        return response()->json($this->roleHasMealAllowance->data()->paginate(10));
    }

    public function store(MealAllowanceRequest $request): JsonResponse
    {
        $data = $request->validated();
        RoleHasMealAllowance::create($data);

        return response()->json([
            'message' => 'data berhasil disimpan',
        ]);
    }


    public function update(MealAllowanceRequest $request, RoleHasMealAllowance $roleHasMealAllowance): JsonResponse
    {
        $data = $request->validated();
        $roleHasMealAllowance->update($data);
        return response()->json([
            'message' => 'data berhasil disimpan',
        ]);
    }


    public function destroy(Request $request, RoleHasMealAllowance $roleHasMealAllowance): JsonResponse
    {
        $implodeID = implode(',', $request->get('id'));
        $explodeID = explode(',', $implodeID);
        $roleHasMealAllowance->whereIn('id', $explodeID)->delete();


        return response()->json([
            'message' => 'data berhasil dihapus',
        ]);
    }
}
