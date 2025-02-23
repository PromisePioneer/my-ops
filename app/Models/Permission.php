<?php

namespace App\Models;

use Laravel\Scout\Searchable;
use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
    use Searchable;

    public function __construct()
    {
        parent::__construct();
    }

    public function toSearchableArray(): array
    {
        return [
            'name' => $this->name
        ];
    }
}
