<?php

namespace App\Policies;

use App\Models\User;

class TransactionPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user): bool
    {
        return $user->can('Lihat Menu Transaksi');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('Tambah Data Transaksi');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user): bool
    {
        return $user->can('Ubah Data Transaksi');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user): bool
    {
        return $user->can('Hapus Data Transaksi');
    }

    public function confirm(User $user): bool
    {
        return $user->can('Konfirmasi Data Transaksi');
    }
}
