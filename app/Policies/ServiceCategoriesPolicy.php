<?php

namespace App\Policies;

use App\Models\User;

class ServiceCategoriesPolicy
{
    public function view(User $user): bool
    {
        return $user->can('Lihat Menu Kategori Layanan');
    }

    public function create(User $user): bool
    {
        return $user->can('Tambah Data Kategori Layanan');
    }

    public function update(User $user): bool
    {
        return $user->can('Update Data Kategori Layanan');
    }

    public function delete(User $user): bool
    {
        return $user->can('Hapus Data Kategori Layanan');
    }
}
