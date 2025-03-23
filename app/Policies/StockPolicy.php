<?php

namespace App\Policies;

use App\Models\User;

class StockPolicy
{

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user): bool
    {
        return $user->can('Lihat Menu Stok Barang');
    }


    public function filter(User $user): bool
    {
        return $user->can('Filter Stok Barang Berdasarkan Cabang');
    }
}
