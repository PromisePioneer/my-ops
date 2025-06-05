<?php

namespace App\Support\Attendances\WorkTime\Repositories;

use App\Models\WorkTime;

class WorkTimeRepository
{

    public static function getDefaultWorkTime(): WorkTime
    {
        return WorkTime::where('is_default', true)->first();
    }

}
