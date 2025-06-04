<?php

namespace App\Http\Controllers;

use AllowDynamicProperties;
use App\Http\Requests\BranchHasDefaultWorkTimeRequest;
use App\Models\BranchHasDefaultWorkTime;
use App\Models\Master\Common\Branch;
use App\Support\Attendances\WorkTime\Service\BranchHasDefaultWorkTimeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

#[AllowDynamicProperties] class BranchHasDefaultWorkTimeController extends Controller
{
    public function __construct()
    {
        $this->branchHasDefaultWorkTimeService = new BranchHasDefaultWorkTimeService();
    }


    public function index(): View
    {
        return view('pages.adms.work-time-settings.index');
    }

    public function data(Request $request): JsonResponse
    {
        return response()->json($this->branchHasDefaultWorkTimeService->data($request));
    }


    public function search(Request $request): JsonResponse
    {

    }

    public function store(BranchHasDefaultWorkTimeRequest $request): JsonResponse
    {
        $this->branchHasDefaultWorkTimeService->store($request);
        return response()->json(['message' => 'Data berhasil disimpan']);
    }


    public function edit(?Branch $branch): JsonResponse
    {
        $branchHasDefaultWorkTime = BranchHasDefaultWorkTime::where('branch_id', $branch->id)->first();
        return response()->json($branchHasDefaultWorkTime);
    }


    public function update(BranchHasDefaultWorkTimeRequest $request, BranchHasDefaultWorkTime $branchHasDefaultWorkTime): JsonResponse
    {
        $this->branchHasDefaultWorkTimeService->update($branchHasDefaultWorkTime, $request);
        return response()->json(['message' => 'Data berhasil disimpan']);
    }
}
