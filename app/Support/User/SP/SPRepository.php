<?php

namespace App\Support\User\SP;

use App\Models\SP;
use Illuminate\Database\Eloquent\Builder;

class SPRepository
{
    public function mainQuery(): Builder
    {
        return SP::with('createdBy', 'user', 'user.branch', 'punishedBy', 'knownBy', 'knownByRole', 'punishedByRole');
    }
}
