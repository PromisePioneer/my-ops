<?php

namespace App\Support\Attendances\WorkTime\Service;

use AllowDynamicProperties;
use App\Http\Requests\BranchDefaultWorkTimeRequest;
use App\Models\BranchDefaultWorkTime;
use App\Models\WorkTime;
use App\Support\Attendances\WorkTime\Repositories\BranchDefaultWorkTimeRepository;
use App\Support\Attendances\WorkTime\Repositories\WorkTimeRepository;
use App\Support\Master\Common\Branch\Repository\BranchRepository;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

#[AllowDynamicProperties] class BranchDefaultWorkTimeService
{
    private static int $perPage = 10;

    public function __construct()
    {
        $this->branchDefaultWorkTimeRepository = new BranchDefaultWorkTimeRepository();
        $this->branchRepository = new BranchRepository();
    }


    public function data(): LengthAwarePaginator
    {
        $workTimes = $this->branchRepository->getBranchAndDefaultWorkTime()->paginate(self::$perPage);
        return self::formattedData($workTimes);
    }

    public function search(Request $request): LengthAwarePaginator
    {
        $search = $request->input('search');
        $data = $this->branchRepository->getBranchAndDefaultWorkTime();
        if (!empty($search)) {
            $data = $this->branchDefaultWorkTimeRepository->searchQuery($data, $search);
        }
        return self::formattedData($data->paginate(self::$perPage));
    }


    public function formattedData(LengthAwarePaginator $workTime): LengthAwarePaginator
    {
        $data = $workTime->getCollection()->map(function ($item) {
            $defaultWorkTime = WorkTimeRepository::getDefaultWorkTime();
            return [
                'id' => $item->id,
                'name' => $item->name,
                'work_time' => WorkTimeService::getWorkTime($item->defaultWorkTime?->workTime ?? $defaultWorkTime),
                'work_time_id' => $item->defaultWorkTime?->workTime?->id ?? $defaultWorkTime->id,
            ];
        });


        $workTime->setCollection($data);
        return $workTime;
    }


    public function store(BranchDefaultWorkTimeRequest $request): void
    {
        BranchDefaultWorkTime::updateOrCreate([
            'branch_id' => $request->branch_id,
        ], [
            'work_time_id' => $request->work_time_id
        ]);
    }


    public function update(BranchDefaultWorkTime $branchHasDefaultWorkTime, BranchDefaultWorkTimeRequest $request): void
    {
        $branchHasDefaultWorkTime->update([
            'work_time_id' => $request->work_time_id,
            'branch_id' => $request->branch_id
        ]);
    }
}
