<?php

namespace App\Http\Controllers\HRIS\Payroll\PayrollConfigurations;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PayrollController extends Controller
{
    private Role $role;

    public function __construct()
    {
        $this->role = new Role();
    }


    public function index(): View
    {
        return view('pages.payroll.index');
    }


    public function getRolesData(Request $request): JsonResponse
    {
        return response()->json($this->role->getData($request));
    }
}
