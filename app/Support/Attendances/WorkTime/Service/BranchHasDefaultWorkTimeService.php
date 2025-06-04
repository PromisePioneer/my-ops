<?php

namespace App\Support\Attendances\WorkTime\Service;

use AllowDynamicProperties;
use App\Http\Requests\BranchHasDefaultWorkTimeRequest;
use App\Models\BranchHasDefaultWorkTime;
use App\Support\Attendances\WorkTime\Repositories\BranchHasDefaultWorkTimeRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

#[AllowDynamicProperties] class BranchHasDefaultWorkTimeService
{
    private static int $perPage = 10;

    public function __construct()
    {
        $this->branchHasDefaultWorkTimeRepository = new BranchHasDefaultWorkTimeRepository();
    }


    public function data(Request $request): LengthAwarePaginator
    {
        $workTimes = $this->branchHasDefaultWorkTimeRepository->getData($request)->paginate(self::$perPage);
        return self::formattedData($workTimes);
    }

    public function search(Request $request): JsonResponse
    {

        return response()->json();
    }


    public function filter(Request $request)
    {

    }


    public function formattedData(LengthAwarePaginator $workTime): LengthAwarePaginator
    {
        $data = $workTime->getCollection()->map(function ($item) {
            return [
                'id' => $item->id,
                'role_name' => $item->role->name,
                'branch_name' => $item->branch->name,
                'name' => $item->workTime->name,
            ];
        });


        $workTime->setCollection($data);
        return $workTime;
    }


    public function store(BranchHasDefaultWorkTimeRequest $request): void
    {
        BranchHasDefaultWorkTime::updateOrCreate([
            'branch_id' => $request->branch_id,
        ], [
            'work_time_id' => $request->work_time_id
        ]);
    }


    public function update(BranchHasDefaultWorkTime $branchHasDefaultWorkTime, BranchHasDefaultWorkTimeRequest $request): void
    {
        $branchHasDefaultWorkTime->update([
            'work_time_id' => $request->work_time_id,
            'branch_id' => $request->branch_id
        ]);
    }
}
