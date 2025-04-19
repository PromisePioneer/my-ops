<?php

namespace App\Policies;

use App\Models\User;

class BroadbandPacketPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user): bool
    {
        return $user->can('Lihat Menu Paket Broadband');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('Tambah Data Paket Broadband');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user): bool
    {
        return $user->can('Update Data Paket Broadband');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user): bool
    {
        return $user->can('Hapus Data Paket Broadband');
    }
}
