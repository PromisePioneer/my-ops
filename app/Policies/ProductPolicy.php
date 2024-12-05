<?php

namespace App\Policies;

use App\Models\User;

class ProductPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function view(User $user): bool
    {
        return $user->can('Lihat Menu Produk');
    }

    public function create(User $user): bool
    {
        return $user->can('Tambah Data Produk');
    }

    public function update(User $user): bool
    {
        return $user->can('Update Data Produk');
    }

    public function delete(User $user): bool
    {
        return $user->can('Hapus Data Produk');
    }
}
