<?php

namespace App\Policies;
use App\Models\User;

class StockWithdrawalPolicy
{

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user): bool
    {
        return $user->can('Lihat Menu Pemakaian Barang');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('Tambah Data Pemakaian Barang');
    }


    public function delete(User $user): bool
    {
        return $user->can('Hapus Data Pemakaian Barang');
    }


    public function filterByBranch(User $user): bool
    {
        return $user->can('Filter Data Pemakaian Barang Berdasarkan Cabang');
    }
}
