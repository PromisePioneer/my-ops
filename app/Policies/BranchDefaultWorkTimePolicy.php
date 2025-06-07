<?php

namespace App\Policies;

use App\Models\BranchDefaultWorkTime;
use App\Models\User;

class BranchDefaultWorkTimePolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user): bool
    {
        return $user->can('Lihat Menu Jam Kerja Berdasarkan Cabang');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('Tambah Data Jam Kerja Berdasarkan Cabang');
    }

    public function reset(User $user): bool
    {
        return $user->can('Reset Data Jam Kerja Berdasarkan Cabang');
    }
}
