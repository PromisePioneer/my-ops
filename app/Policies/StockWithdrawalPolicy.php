<?php

namespace App\Policies;

use App\Models\StockWithdrawal;
use App\Models\User;

class StockWithdrawalPolicy
{

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, StockWithdrawal $stockWithdrawal): bool
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
}
