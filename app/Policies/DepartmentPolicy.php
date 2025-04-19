<?php

namespace App\Policies;

use App\Models\User;

class DepartmentPolicy
{
    public function view(User $user): bool
    {
        return $user->can('Lihat Menu Departemen');
    }

    public function create(User $user): bool
    {
        return $user->can('Tambah Data Departemen');
    }

    public function update(User $user): bool
    {
        return $user->can('Edit Data Departemen');
    }

    public function delete(User $user): bool
    {
        return $user->can('Hapus Data Departemen');
    }
}
