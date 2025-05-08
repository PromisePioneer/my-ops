<?php

namespace App\Support\Attendances\WeekHoliday\Repository;

use App\Models\WeekHoliday;
use Illuminate\Support\Collection;

class WeekHolidayRepository
{

    public function getBasedOnUserId(Collection $userIds)
    {
        return WeekHoliday::whereIn('user_id', $userIds)
            ->get()
            ->keyBy('user_id');
    }

}
