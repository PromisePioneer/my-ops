<?php

namespace App\Policies;

use App\Models\User;

class TransactionPolicy
{
    public function view(User $user): bool
    {
        return $user->can('Lihat Menu Transaksi');
    }

    public function create(User $user): bool
    {
        return $user->can('Tambah Data Transaksi');
    }

    public function update(User $user): bool
    {
        return $user->can('Ubah Data Transaksi');
    }

    public function delete(User $user): bool
    {
        return $user->can('Hapus Data Transaksi');
    }

    public function lockStatus(User $user): bool
    {
        return $user->can('Kunci Data Transaksi');
    }

    public function confirm(User $user): bool
    {
        return $user->can('Setujui Data Transaksi');
    }


    /**
     * Determine whether the user can view any models.
     */
    public function viewInitialInventoryBalance(User $user): bool
    {
        return $user->can('Lihat Menu Saldo Awal Persediaan');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function createInitialInventoryBalance(User $user): bool
    {
        return $user->can('Tambah Data Saldo Awal Persediaan');
    }


    /**
     * Determine whether the user can update the model.
     */
    public function updateInitialInventoryBalance(User $user): bool
    {
        return $user->can('Edit Data Saldo Awal Persediaan');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function deleteInitialInventoryBalance(User $user): bool
    {
        return $user->can('Hapus Data Saldo Awal Persediaan');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function filterByBranchInitialInventoryBalance(User $user): bool
    {
        return $user->can('Filter Saldo Awal Persediaan Berdasarkan Cabang');
    }


    public function confirmInitialInventoryBalance(User $user): bool
    {
        return $user->can('Konfirmasi Data Saldo Awal Persediaan');
    }
}
