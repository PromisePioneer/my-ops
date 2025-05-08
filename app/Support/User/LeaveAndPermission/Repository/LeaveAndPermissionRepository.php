<?php

namespace App\Support\User\LeaveAndPermission\Repository;

use App\Models\LeaveAndPermission;

class LeaveAndPermissionRepository
{
    public function getBasedOnPeriodAndUserId(int $userId, string $firstLeaveStartDate, string $lastLeaveEndDate)
    {
        return LeaveAndPermission::where('user_id', $userId)
            ->whereBetween('start_date', [$firstLeaveStartDate, $lastLeaveEndDate])
            ->get();
    }
}
