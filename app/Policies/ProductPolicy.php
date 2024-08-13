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
        return $user->can('lihat produk');
    }

    public function create(User $user): bool
    {
        return $user->can('tambah produk');
    }

    public function update(User $user, Product $product): bool
    {
        return $user->can('update produk');
    }

    public function delete(User $user): bool
    {
        return $user->can('delete produk');
    }
}
