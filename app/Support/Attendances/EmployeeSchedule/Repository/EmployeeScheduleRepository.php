<?php

namespace App\Support\Attendances\EmployeeSchedule\Repository;

use App\Models\EmployeeSchedule;

class EmployeeScheduleRepository
{

    public function getBasedOnPeriodsAndAbsentId($startDate, $endDate, $absentIds)
    {
        return EmployeeSchedule::whereIn('employee_id', $absentIds)
            ->whereBetween('start_date', [$startDate, $endDate])
            ->where('status', 'L')
            ->get()
            ->groupBy('employee_id');
    }

}
