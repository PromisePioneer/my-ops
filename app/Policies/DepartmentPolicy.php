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
        return $user->can('Lihat Departemen');
    }

    public function create(User $user): bool
    {
        return $user->can('Tambah Departemen');
    }

    public function update(User $user): bool
    {
        return $user->can('Update Departemen');
    }

    public function delete(User $user): bool
    {
        return $user->can('Hapus Departemen');
    }
}
