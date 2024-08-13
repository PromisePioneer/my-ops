<?php

namespace App\Policies;

use App\Models\User;

class ServiceCategoriesPolicy
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
        return $user->can('lihat kategori layanan');
    }

    public function create(User $user): bool
    {
        return $user->can('tambah kategori layanan');
    }

    public function update(User $user): bool
    {
        return $user->can('update kategori layanan');
    }

    public function delete(User $user): bool
    {
        return $user->can('hapus kategori layanan');
    }
}
