<?php

namespace App\Policies;

use App\Models\BranchRoleDefaultWorkTime;
use App\Models\User;

class BranchRoleDefaultWorkTimePolicy
{

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, BranchRoleDefaultWorkTime $branchRoleDefaultWorkTime): bool
    {
        return $user->can('Lihat Menu Jam Kerja Jabatan Di Cabang');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('Tambah Data Jam Kerja Jabatan Di Cabang');
    }


    /**
     * Determine whether the user can delete the model.
     */
    public function reset(User $user): bool
    {
        return $user->can('Reset Data Jam Kerja Jabatan Di Cabang');
    }

}
