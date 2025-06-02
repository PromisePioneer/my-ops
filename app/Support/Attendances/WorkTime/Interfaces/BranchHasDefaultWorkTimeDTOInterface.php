<?php

namespace App\Support\Attendances\WorkTime\Interfaces;

use Illuminate\Pagination\LengthAwarePaginator;

interface BranchHasDefaultWorkTimeDTOInterface
{
    public function getData(LengthAwarePaginator $workTime): LengthAwarePaginator;
}
