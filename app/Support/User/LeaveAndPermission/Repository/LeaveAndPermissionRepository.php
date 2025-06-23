<?php

namespace App\Support\User\LeaveAndPermission\Repository;

use AllowDynamicProperties;
use App\Models\LeaveAndPermission;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

#[AllowDynamicProperties] class LeaveAndPermissionRepository
{
    public function __construct()
    {
        $this->leaveAndPermission = new LeaveAndPermission();
    }


    public function leavesMainQuery(): Builder
    {
        return $this->leaveAndPermission->query()
            ->with('accBy', 'user', 'user.userHasArea', 'user.branch', 'user.roles.department', 'user.company')
            ->orderByDesc('created_at');
    }


    public function searchQuery(Builder $query, string $search): Builder
    {
        return $query->where(function ($query) use ($search) {
            $query->whereHas('user', function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            });
        });
    }


    public function getOwnLeaves(Request $request): Builder
    {
        return $this->leaveAndPermission
            ->with('accBy', 'user', 'user.userHasArea', 'user.branch', 'user.roles.department')
            ->where('user_id', $request->user()->id)
            ->orderBy('created_at');
    }


}
