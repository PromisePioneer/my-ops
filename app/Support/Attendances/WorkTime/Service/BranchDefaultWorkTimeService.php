<?php

namespace App\Support\Attendances\WorkTime\Service;

use AllowDynamicProperties;
use App\Http\Requests\BranchDefaultWorkTimeRequest;
use App\Models\BranchDefaultWorkTime;
use App\Models\WorkTime;
use App\Support\Attendances\WorkTime\Repositories\BranchDefaultWorkTimeRepository;
use App\Support\Master\Common\Branch\Repository\BranchRepository;
use Illuminate\Http\JsonResponse;
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


    public function data(Request $request): LengthAwarePaginator
    {
        $workTimes = $this->branchRepository->getBranchAndDefaultWorkTime()->paginate(self::$perPage);
        return self::formattedData($workTimes);
    }

    public function search(Request $request): JsonResponse
    {

        return response()->json();
    }


    public function filter(Request $request)
    {

        return response()->json();
    }


    public function formattedData(LengthAwarePaginator $workTime): LengthAwarePaginator
    {
        $data = $workTime->getCollection()->map(function ($item) {

            $workTime = $item->branchHasDefaultWorkTime;

            if (count($workTime) > 0) {
                $branchHasDefaultWorkTime = $workTime->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'name' => "{$item->workTime->name} ({$item->workTime->clock_in} - {$item->workTime->clock_out})",
                    ];
                });
            } else {
                $branchHasDefaultWorkTime = WorkTime::where('name', 'Pagi')->get()->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'name' => "{$item->name} ({$item->clock_in} - {$item->clock_out})",
                    ];
                });
            }

            return [
                'id' => $item->id,
                'name' => $item->name,
                'work_time' => $branchHasDefaultWorkTime,
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
