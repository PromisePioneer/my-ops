<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

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

        $totalEmp = User::withoutRole('Super Admin')->where('active', true)->count();
        $totalBranch = Branch::whereNull('parent_id')->count();

        return response()->json([
            'totalEmp' => $totalEmp,
            'totalBranch' => $totalBranch,
        ]);
    }


    public function branchManagerDasboard(Request $request): JsonResponse
    {
        $totalActiveEmp = User::where('branch_id', $request->user()->branch_id)->where('active', true)->count();
        $totalUnactiveEmp = User::where('branch_id', $request->user()->branch_id)->where('active', false)->count();
        return response()->json([
            'totalActiveEmployee' => $totalActiveEmp,
            'totalUnactiveEmployee' => $totalUnactiveEmp
        ]);
    }


    public function index(): View
    {
       return view('home');
    }
}
