<?php

namespace App\Http\Controllers;

use AllowDynamicProperties;
use App\Http\Requests\BranchDefaultWorkTimeRequest;
use App\Models\BranchDefaultWorkTime;
use App\Models\Master\Common\Branch;
use App\Support\Attendances\WorkTime\Service\BranchDefaultWorkTimeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

#[AllowDynamicProperties] class BranchDefaultWorkTimeController extends Controller
{
    public function __construct()
    {
        $this->branchDefaultWorkTimeService = new BranchDefaultWorkTimeService();
    }


    public function index(): View
    {
        return view('pages.adms.work-time-settings.index');
    }

    public function data(Request $request): JsonResponse
    {
        return response()->json($this->branchDefaultWorkTimeService->data($request));
    }


    public function search(Request $request): JsonResponse
    {

    }

    public function store(BranchDefaultWorkTimeRequest $request): JsonResponse
    {
        $this->branchDefaultWorkTimeService->store($request);
        return response()->json(['message' => 'Data berhasil disimpan']);
    }


    public function edit(?Branch $branch): JsonResponse
    {
        $branchHasDefaultWorkTime = BranchDefaultWorkTime::where('branch_id', $branch->id)->first();
        return response()->json($branchHasDefaultWorkTime);
    }


    public function update(BranchDefaultWorkTimeRequest $request, BranchDefaultWorkTime $branchHasDefaultWorkTime): JsonResponse
    {
        $this->branchDefaultWorkTimeService->update($branchHasDefaultWorkTime, $request);
        return response()->json(['message' => 'Data berhasil disimpan']);
    }
}
