<?php

namespace App\Service\User\SP;

use App\Models\SP;
use Illuminate\Database\Eloquent\Builder;

class SPRepository
{
    public function mainQuery(): Builder
    {
        return SP::with('createdBy', 'user', 'branch');
    }
}
