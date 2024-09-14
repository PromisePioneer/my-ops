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
        return $user->can('Lihat Jabatan');
    }

    public function create(User $user): bool
    {
        return $user->can('Tambah Jabatan');
    }

    public function update(User $user): bool
    {
        return $user->can('Update Jabatan');
    }

    public function delete(User $user): bool
    {
        return $user->can('Hapus Jabatan');
    }
}
