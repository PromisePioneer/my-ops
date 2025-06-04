<?php

namespace App\Support\Attendances\WorkTime\Repositories;

use App\Models\BranchDefaultWorkTime;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class BranchDefaultWorkTimeRepository
{
    public function getData(Request $request): Builder
    {
        return BranchDefaultWorkTime::with('branch', 'workTime', 'role')
            ->when(!empty($request->user()->branch_id), function ($query) use ($request) {
                $query->where('branch_id', $request->user()->branch_id);
            });
    }


    public function getByUserBranchId(Request $request)
    {
        return BranchDefaultWorkTime::with('branch', 'workTime')
            ->where('branch_id', $request->user()->branch_id)
            ->get();
    }

    public function searchQuery(Request $request)
    {
        return BranchDefaultWorkTime::join('branches', 'branches.id', '=', 'branch_has_default_work_times.branch_id')
            ->join('work_time', 'work_time.id', '=', 'branch_has_default_work_time.work_time_id');
    }
}
