<?php

namespace App\Http\Controllers;

use AllowDynamicProperties;
use App\Http\Requests\BranchRoleDefaultWorkTimeRequest;
use App\Models\BranchRoleDefaultWorkTime;
use App\Models\Master\Common\Branch;
use App\Models\Role;
use App\Models\WorkTime;
use App\Support\Attendances\WorkTime\Service\BranchRoleDefaultWorkTimeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

#[AllowDynamicProperties] class BranchRoleDefaultWorkTimeController extends Controller
{
    public function __construct()
    {
        $this->branchAndRoleDefaultWorkTimeService = new BranchRoleDefaultWorkTimeService();
    }


    public function index(Branch $branch): View
    {
        return view('pages.adms.work-time-settings.branch-role.index', compact('branch'));
    }


    public function data(Branch $branch): JsonResponse
    {
        return response()->json($this->branchAndRoleDefaultWorkTimeService->data($branch));
    }


    public function search(Request $request, Branch $branch): JsonResponse
    {
        return response()->json($this->branchAndRoleDefaultWorkTimeService->search($request, $branch));
    }


    public function filter(Request $request)
    {

    }


    public function store(Branch $branch, BranchRoleDefaultWorkTimeRequest $request): JsonResponse
    {
        $this->branchAndRoleDefaultWorkTimeService->store($branch, $request);
        return response()->json(['message' => 'data berhasil disimpan']);
    }


    public function edit(Branch $branch, Role $role, WorkTime $workTime): JsonResponse
    {
        $roleDefaultWorkTime = BranchRoleDefaultWorkTime::where('role_id', $role->id)
            ->where('branch_id', $branch->id)
            ->where('work_time_id', $workTime->id)
            ->first();

        return response()->json($roleDefaultWorkTime);
    }


    public function destroy(Branch $branch, Role $role, WorkTime $workTime)
    {
        BranchRoleDefaultWorkTime::where('role_id', $role->id)->where('branch_id', $branch->id)->where('work_time_id', $workTime->id)->delete();
        return response()->json(['message' => 'data berhasil dihapus']);
    }
}
