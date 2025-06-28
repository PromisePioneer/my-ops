<?php

namespace App\Support\Master\Common\SKL\Repository;

use App\Models\Master\Common\SKL;
use Illuminate\Database\Eloquent\Builder;

class SKLRepository
{

    public function __construct()
    {
        $this->skl = new SKL();
    }

    public function getSKL(): Builder
    {
        return $this->skl->query()->orderBy('name');
    }

    public function getTrashedSKL(): Builder
    {
        return $this->skl->onlyTrashed();
    }
}
