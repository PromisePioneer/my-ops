<?php

namespace App\Support\Attendances\WorkTime\Repositories;

use App\Models\BranchHasDefaultWorkTime;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class BranchHasDefaultWorkTimeRepository
{
    public function getData(Request $request): Builder
    {
        return BranchHasDefaultWorkTime::with('branch', 'workTime')
            ->when(!empty($request->user()->branch_id), function ($query) use ($request) {
                $query->where('branch_id', $request->user()->branch_id);
            });
    }
}
