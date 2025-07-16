<?php

namespace App\Policies;

use App\Models\StockMutation;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class StockMutationPolicy
{

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user): bool
    {
        return $user->can('Lihat Menu Mutasi Barang');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('Tambah Data Mutasi Barang');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function cancelDelivery(User $user): bool
    {
        return $user->can('Batalkan Pengiriman Barang');
    }


    public function viewDetail(User $user): bool
    {
        return $user->can('Lihat Detail Mutasi Barang');
    }


    public function receive(User $user): bool
    {
        return $user->can('Terima Mutasi Barang');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user): bool
    {
        return $user->can('Hapus Data Mutasi Barang');
    }
}
