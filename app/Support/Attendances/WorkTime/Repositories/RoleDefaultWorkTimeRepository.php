<?php

namespace App\Support\Attendances\WorkTime\Repositories;

use App\Models\RoleDefaultWorkTime;
use App\Models\WorkTime;

class RoleDefaultWorkTimeRepository
{
    public function getWorkTime()
    {
        return RoleDefaultWorkTime::with('workTime')->get();
    }


    public function searchQuery($query, $search)
    {
        return $query->where('name', 'like', '%' . $search . '%');
    }


    public static function getDefaultWorkTime(int $roleId): null|WorkTime
    {
        $roleDefaultWorkTime = RoleDefaultWorkTime::where('role_id', $roleId)->first();


        if (!empty($roleDefaultWorkTime)) {
            return WorkTime::where('id', $roleDefaultWorkTime->work_time_id)->first();
        }

        return null;
    }
}
