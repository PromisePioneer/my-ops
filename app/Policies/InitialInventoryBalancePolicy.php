<?php

namespace App\Policies;

use App\Models\InitialInventoryBalance;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class InitialInventoryBalancePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function view(User $user): bool
    {
        return $user->can('Lihat Menu Saldo Awal Persediaan');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function create(User $user): bool
    {
        return $user->can('Tambah Data Saldo Awal Persediaan');
    }


    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user): bool
    {
        return $user->can('Edit Data Saldo Awal Persediaan');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user): bool
    {
        return $user->can('Hapus Data Saldo Awal Persediaan');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function filterByBranch(User $user): bool
    {
        return $user->can('Filter Saldo Awal Persediaan Berdasarkan Cabang');
    }


    public function confirm(User $user): bool
    {
        return $user->can('Konfirmasi Data Saldo Awal Persediaan');
    }
}
