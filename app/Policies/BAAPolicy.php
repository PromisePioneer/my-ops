<?php

namespace App\Policies;

use App\Models\BAA;
use App\Models\User;

class BAAPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('Lihat Menu BAA');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, BAA $bAA): bool
    {
        return $user->can('Lihat Data BAA');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('Tambah Data BAA');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user): bool
    {
        return $user->can('Edit Data BAA');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user): bool
    {
        return $user->can('Hapus Data BAA');
    }

    public function viewDetail(User $user): bool
    {
        return $user->can('Lihat Detail Data BAA');
    }

    public function print(User $user): bool
    {
        return $user->can('Print Data BAA');
    }

    public function confirm(User $user): bool
    {
        return $user->can('Konfirmasi Data BAA');
    }

    public function createOrUpdateSPK(User $user): bool
    {
        return $user->can('Buat SPK / Ubah SPK');
    }

    public function printSPK(User $user): bool
    {
        return $user->can('Buat SPK / Ubah SPK');
    }
}
