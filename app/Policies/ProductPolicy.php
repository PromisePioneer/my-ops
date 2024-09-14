<?php

namespace App\Policies;

use App\Models\Product;
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
        return $user->can('Lihat Produk');
    }

    public function create(User $user): bool
    {
        return $user->can('Tambah Produk');
    }

    public function update(User $user, Product $product): bool
    {
        return $user->can('Update Produk');
    }

    public function delete(User $user): bool
    {
        return $user->can('Hapus Produk');
    }
}
