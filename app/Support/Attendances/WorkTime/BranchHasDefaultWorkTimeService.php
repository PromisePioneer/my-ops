<?php

namespace App\Support\Attendances\WorkTime;

use AllowDynamicProperties;
use App\Models\BranchHasDefaultWorkTime;
use App\Support\Attendances\WorkTime\DTO\BranchHasDefaultWorkTimeDTO;
use App\Support\Attendances\WorkTime\Repositories\BranchHasDefaultWorkTimeRepository;
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
        return (new BranchHasDefaultWorkTimeDTO)->getData($workTimes);
    }

    public function search(Request $request)
    {

    }


    public function filter(Request $request)
    {

    }


    public function store(Request $request): bool
    {
        $branchHasDefaultWorkTimeDTO = new BranchHasDefaultWorkTimeDTO($request->work_time_id, $request->branch_id);
        return BranchHasDefaultWorkTime::create([
            'branch_id' => $branchHasDefaultWorkTimeDTO->branchId,
            'work_time_id' => $branchHasDefaultWorkTimeDTO->workTimeId
        ]);
    }
}
