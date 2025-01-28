<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Jmrashed\Zkteco\Lib\ZKTeco;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function summary(Request $request): JsonResponse
    {
        $roles = Role::with('department')->whereHas('department', function ($query) {
            $query->where('name', 'Operational');
        })->get();

        $roleAllowed = [];

//        foreach ($roles as $role) {
//            $roleAllowed[] = $role->name;
//        }

//        if ($request->user()->hasAnyRole($roleAllowed)) {
        $totalEmp = User::withoutRole('Super Admin')->where('active', true)->count();
        $totalBranch = Branch::count();
        return response()->json([
            'totalEmp' => $totalEmp,
            'totalBranch' => $totalBranch,
        ]);
//        }

        return response()->json([
            'status' => 'error',
        ]);
    }


    public function index(): View
    {
       return view('home');
    }
}
