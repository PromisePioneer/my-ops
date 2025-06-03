<?php

namespace App\Support\Attendances\WorkTime\Service;

use AllowDynamicProperties;
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
        return self::formattedData($workTimes);
    }

    public function search(Request $request)
    {

    }


    public function filter(Request $request)
    {

    }


    public function formattedData(LengthAwarePaginator $workTime): LengthAwarePaginator
    {
        $data = $workTime->getCollection()->map(function ($item) {
            return [
                'id' => $item->id,
                'branch_name' => $item->branch,
                'name' => $item->workTime->name,
            ];
        });


        $workTime->setCollection($data);
        return $workTime;
    }


    public function store(Request $request): bool
    {
    }
}
