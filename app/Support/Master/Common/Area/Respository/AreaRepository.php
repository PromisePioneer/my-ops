<?php

namespace App\Support\Master\Common\Area\Respository;

use AllowDynamicProperties;
use App\Models\Area;
use Illuminate\Database\Eloquent\Builder;

#[AllowDynamicProperties] class AreaRepository
{
    public function __construct()
    {
        $this->area = new Area();
    }


    public function getData(): Builder|Area
    {
        return $this->area->with(['branch', 'department'])->withCount('areaHasUser');
    }


    public function searchAreaQuery(Builder $query): Builder|Area
    {
       return $query->leftJoin('branches', 'areas.branch_id', '=', 'branches.id')
            ->leftJoin('departments', 'areas.department_id', '=', 'departments.id')
            ->select('areas.*', 'branches.name as branch_name', 'departments.name as department_name');
    }
}
