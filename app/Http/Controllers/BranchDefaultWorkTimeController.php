<?php

namespace App\Http\Controllers;

use AllowDynamicProperties;
use App\Http\Requests\BranchDefaultWorkTimeRequest;
use App\Models\BranchDefaultWorkTime;
use App\Models\Master\Common\Branch;
use App\Models\WorkTime;
use App\Support\Attendances\WorkTime\Service\BranchDefaultWorkTime\BranchDefaultWorkTimeService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

#[AllowDynamicProperties] class BranchDefaultWorkTimeController extends Controller
{
    public function __construct()
    {
        $this->branchDefaultWorkTimeService = new BranchDefaultWorkTimeService();
    }

    /**
     * @throws AuthorizationException
     */
    public function data(Request $request): JsonResponse
    {
        $this->authorize('view', BranchDefaultWorkTime::class);
        return response()->json($this->branchDefaultWorkTimeService->data($request));
    }


    /**
     * @throws AuthorizationException
     */
    public function search(Request $request): JsonResponse
    {
        $this->authorize('view', BranchDefaultWorkTime::class);
        return response()->json($this->branchDefaultWorkTimeService->search($request));
    }

    public function store(BranchDefaultWorkTimeRequest $request): JsonResponse
    {
        $this->authorize('create', BranchDefaultWorkTime::class);
        $this->branchDefaultWorkTimeService->store($request);
        return response()->json(['message' => 'Data berhasil disimpan']);
    }


    /**
     * @throws AuthorizationException
     */
    public function edit(?Branch $branch): JsonResponse
    {
        $this->authorize('create', BranchDefaultWorkTime::class);
        $branchHasDefaultWorkTime = BranchDefaultWorkTime::where('branch_id', $branch->id)->first();
        return response()->json($branchHasDefaultWorkTime);
    }


    /**
     * @throws AuthorizationException
     */
    public function update(BranchDefaultWorkTimeRequest $request, BranchDefaultWorkTime $branchHasDefaultWorkTime): JsonResponse
    {
        $this->authorize('create', BranchDefaultWorkTime::class);
        $this->branchDefaultWorkTimeService->update($branchHasDefaultWorkTime, $request);
        return response()->json(['message' => 'Data berhasil disimpan']);
    }


    /**
     * @throws AuthorizationException
     */
    public function showDefaultWorkTime(Branch $branch): JsonResponse
    {
        $this->authorize('view', BranchDefaultWorkTime::class);
        return response()->json($this->branchDefaultWorkTimeService->getBranchDefaultWorkTime($branch));
    }


    /**
     * @throws AuthorizationException
     */
    public function destroy(Branch $branch, WorkTime $workTime): JsonResponse
    {
        $this->authorize('reset', BranchDefaultWorkTime::class);
        BranchDefaultWorkTime::where('branch_id', $branch->id)->where('work_time_id', $workTime->id)->delete();
        return response()->json(['message' => 'Data berhasil direset']);
    }
}
