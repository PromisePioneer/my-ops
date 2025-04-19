<?php

namespace App\Policies;

use App\Models\User;

class RolePolicy
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
        return $user->can('Lihat Menu Jabatan');
    }

    public function create(User $user): bool
    {
        return $user->can('Tambah Data Jabatan');
    }

    public function update(User $user): bool
    {
        return $user->can('Edit Data Jabatan');
    }

    public function delete(User $user): bool
    {
        return $user->can('Hapus Data Jabatan');
    }
}
