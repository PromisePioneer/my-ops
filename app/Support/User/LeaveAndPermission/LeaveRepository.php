<?php

namespace App\Support\User\LeaveAndPermission;

use App\Models\LeaveAndPermission;
use Illuminate\Database\Eloquent\Builder;

class LeaveRepository
{
    public function leavesMainQuery(): Builder
    {
        return LeaveAndPermission::with('accBy', 'user', 'user.userHasArea', 'user.branch');
    }
}
