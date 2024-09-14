<?php

namespace App\Policies;

use App\Models\User;

class SubAccountPolicy
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
        return $user->can('Lihat Sub Akun');
    }

    public function create(User $user): bool
    {
        return $user->can('Tambah Sub Akun');
    }

    public function update(User $user): bool
    {
        return $user->can('Update Sub Akun');
    }

    public function delete(User $user): bool
    {
        return $user->can('Hapus Sub Akun');
    }

    public function import(User $user): bool
    {
        return $user->can('Import Sub Akun');
    }
}
