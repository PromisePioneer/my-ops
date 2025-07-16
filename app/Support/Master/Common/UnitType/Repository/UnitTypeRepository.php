<?php

namespace App\Support\Master\Common\UnitType\Repository;

use AllowDynamicProperties;
use App\Models\Master\Common\UnitType;
use Illuminate\Database\Eloquent\Builder;

#[AllowDynamicProperties] class UnitTypeRepository
{

    public function __construct()
    {
        $this->unitType = new UnitType();
    }


    public function data(): Builder
    {
        return $this->unitType->query()->orderBy('name');
    }

    public function findById(int|string|null $id): ?UnitType
    {
        return $this->unitType->query()->find($id);
    }


    public function archivedData(): Builder
    {
        return $this->unitType->query()->onlyTrashed()->orderBy('name');
    }
}
