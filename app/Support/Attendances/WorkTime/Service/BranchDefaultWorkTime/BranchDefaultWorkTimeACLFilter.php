<?php

namespace App\Support\Attendances\WorkTime\Service\BranchDefaultWorkTime;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder;


class BranchDefaultWorkTimeACLFilter
{
    public static function apply(EloquentBuilder|Builder $query, $request): EloquentBuilder|Builder
    {
        if (!empty($request->user()->branch_id)) {
            $query->where('id', $request->user()->branch_id);
        }
        return $query;
    }
}
