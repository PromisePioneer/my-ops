<?php

namespace App\Policies;

use App\Models\User;

class ServiceCategoriesPolicy
{
    public function __construct()
    {
        //
    }

    public function view(User $user): bool
    {
        return $user->can('Lihat Kategori Layanan');
    }

    public function create(User $user): bool
    {
        return $user->can('Tambah Kategori Layanan');
    }

    public function update(User $user): bool
    {
        return $user->can('Update Kategori Layanan');
    }

    public function delete(User $user): bool
    {
        return $user->can('Hapus Kategori Layanan');
    }
}
