<?php

namespace App\Policies;

use App\Models\User;

class DepartmentPolicy
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
        return $user->can('lihat department');
    }

    public function create(User $user): bool
    {
        return $user->can('tambah department');
    }

    public function update(User $user): bool
    {
        return $user->can('update department');
    }

    public function delete(User $user): bool
    {
        return $user->can('hapus department');
    }
}
