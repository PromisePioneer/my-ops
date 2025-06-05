<?php

namespace App\Support\Attendances\WorkTime\Repositories;

use App\Models\RoleDefaultWorkTime;

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
}
