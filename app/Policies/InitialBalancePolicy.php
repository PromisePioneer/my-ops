<?php

namespace App\Policies;

use App\Models\User;

class InitialBalancePolicy
{

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user): bool
    {
        return $user->can('Lihat Menu Saldo Awal');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('Tambah Data Saldo Awal');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user): bool
    {
        return $user->can('Edit Data Saldo Awal');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user): bool
    {
        return $user->can('Hapus Data Saldo Awal');
    }


    public function filterBranch(User $user): bool
    {
        return $user->can('Filter Data Berdasarkan Cabang');
    }

}
