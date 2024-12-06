<?php

namespace App\Policies;

use App\Models\User;

class CompanyPolicy
{
    public function view(User $user): bool
    {
        return $user->can('Lihat Menu Data Perusahaan');
    }

    public function create(User $user): bool
    {
        return $user->can('Tambah Data Perusahaan');
    }

    public function update(User $user): bool
    {
        return $user->can('Edit Data Perusahaan');
    }

    public function delete(User $user): bool
    {
        return $user->can('Hapus Data Perusahaan');
    }
}
