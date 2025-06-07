<?php

namespace App\Policies;

use App\Models\User;

class RoleDefaultWorkTimePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function view(User $user): bool
    {
        return $user->can('Lihat Menu Jam Kerja Berdasarkan Jabatan');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('Tambah Data Jam Kerja Berdasarkan Jabatan');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function reset(User $user): bool
    {
        return $user->can('Reset Data Jam Kerja Berdasarkan Jabatan');
    }
}
