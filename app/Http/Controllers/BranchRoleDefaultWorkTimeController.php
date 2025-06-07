<?php

namespace App\Http\Controllers;

use AllowDynamicProperties;
use App\Http\Requests\BranchRoleDefaultWorkTimeRequest;
use App\Models\BranchRoleDefaultWorkTime;
use App\Models\Master\Common\Branch;
use App\Models\Role;
use App\Models\WorkTime;
use App\Support\Attendances\WorkTime\Service\BranchRoleDefaultWorkTimeService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

#[AllowDynamicProperties] class BranchRoleDefaultWorkTimeController extends Controller
{
    public function __construct()
    {
        $this->branchAndRoleDefaultWorkTimeService = new BranchRoleDefaultWorkTimeService();
    }


    /**
     * @throws AuthorizationException
     */
    public function index(Branch $branch): View
    {
        $this->authorize('view', BranchRoleDefaultWorkTime::class);
        return view('pages.adms.work-time-settings.branch-role.index', compact('branch'));
    }


    /**
     * @throws AuthorizationException
     */
    public function data(Branch $branch): JsonResponse
    {
        $this->authorize('view', BranchRoleDefaultWorkTime::class);
        return response()->json($this->branchAndRoleDefaultWorkTimeService->data($branch));
    }


    /**
     * @throws AuthorizationException
     */
    public function search(Request $request, Branch $branch): JsonResponse
    {
        $this->authorize('view', BranchRoleDefaultWorkTime::class);
        return response()->json($this->branchAndRoleDefaultWorkTimeService->search($request, $branch));
    }


    /**
     * @throws AuthorizationException
     */
    public function store(Branch $branch, BranchRoleDefaultWorkTimeRequest $request): JsonResponse
    {
        $this->authorize('create', BranchRoleDefaultWorkTime::class);
        $this->branchAndRoleDefaultWorkTimeService->store($branch, $request);
        return response()->json(['message' => 'data berhasil disimpan']);
    }


    /**
     * @throws AuthorizationException
     */
    public function edit(Branch $branch, Role $role, WorkTime $workTime): JsonResponse
    {
        $this->authorize('create', BranchRoleDefaultWorkTime::class);
        $roleDefaultWorkTime = BranchRoleDefaultWorkTime::where('role_id', $role->id)
            ->where('branch_id', $branch->id)
            ->where('work_time_id', $workTime->id)
            ->first();

        return response()->json($roleDefaultWorkTime);
    }


    /**
     * @throws AuthorizationException
     */
    public function destroy(Branch $branch, Role $role, WorkTime $workTime): JsonResponse
    {
        $this->authorize('reset', BranchRoleDefaultWorkTime::class);
        BranchRoleDefaultWorkTime::where('role_id', $role->id)->where('branch_id', $branch->id)->where('work_time_id', $workTime->id)->delete();
        return response()->json(['message' => 'data berhasil dihapus']);
    }
}
