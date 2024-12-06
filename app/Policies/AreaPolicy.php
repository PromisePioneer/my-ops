<?php

namespace App\Policies;

use App\Models\User;

class AreaPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user): bool
    {
        return $user->can('Lihat Menu Area');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('Tambah Data Area');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user): bool
    {
        return $user->can('Edit Data Area');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user): bool
    {
        return $user->can('Hapus Data Area');
    }

    public function viewDetail(User $user): bool
    {
        return $user->can('Lihat Detail Data Area');
    }

}
