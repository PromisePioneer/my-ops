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
        return $user->can('lihat role');
    }

    public function create(User $user): bool
    {
        return $user->can('tambah role');
    }

    public function update(User $user): bool
    {
        return $user->can('update role');
    }

    public function delete(User $user): bool
    {
        return $user->can('hapus role');
    }
}
