<?php

namespace App\Support\Attendances\WorkTime\DTO;

use App\Support\Attendances\WorkTime\Interfaces\BranchHasDefaultWorkTimeDTOInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class BranchHasDefaultWorkTimeDTO implements BranchHasDefaultWorkTimeDTOInterface
{


    public function __construct(
        public readonly int $workTimeId,
        public readonly int $branchId,
    )
    {
    }

    public function getData(LengthAwarePaginator $workTime): LengthAwarePaginator
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


    public function fromArray(array $data): self
    {
        return new self(
            workTimeId: $data['work_time_id'],
            branchId: $data['branch_id'],
        );
    }
}
