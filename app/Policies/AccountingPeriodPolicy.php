<?php

namespace App\Policies;

use App\Models\AccountingPeriod;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AccountingPeriodPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function update(User $user): bool
    {
        return $user->can('Ubah Periode Pembukuan');
    }
}
