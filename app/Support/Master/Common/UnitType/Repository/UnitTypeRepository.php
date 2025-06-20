<?php

namespace App\Support\Master\Common\UnitType\Repository;

use AllowDynamicProperties;
use App\Models\Master\Common\UnitType;

#[AllowDynamicProperties] class UnitTypeRepository
{

    public function __construct()
    {
        $this->unitType = new UnitType();
    }

    public function findById(int|string|null $id): ?UnitType
    {
        return $this->unitType->query()->find($id);
    }
}
