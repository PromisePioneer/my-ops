<?php

namespace App\Support\User\SK\Repository;

use AllowDynamicProperties;
use App\Models\SK;
use Illuminate\Database\Eloquent\Builder;

#[AllowDynamicProperties] class SKRepository
{
    public function __construct()
    {
        $this->sk = new SK();
    }

    public function data(): Builder
    {
        return $this->sk
            ->query()
            ->with('user', 'oldBranch', 'newBranch', 'oldRole', 'newRole');

    }
}
