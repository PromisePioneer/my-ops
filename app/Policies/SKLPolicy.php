<?php

namespace App\Policies;

use App\Models\User;

class SKLPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user): bool
    {
        return $user->can('Lihat Menu SKL');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('Tambah Data SKL');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user): bool
    {
        return $user->can('Edit Data SKL');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user): bool
    {
        return $user->can('Hapus Data SKL');
    }

    public function viewArchives(User $user): bool
    {
        return $user->can('Lihat Data Arsip SKL');
    }
}
