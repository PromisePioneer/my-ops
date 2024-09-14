<?php

namespace App\Policies;

use App\Models\User;

class PermissionPolicy
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
        return $user->can('Lihat Hak Akses');
    }


    public function create(User $user): bool
    {
        return $user->can('Tambah Hak Akses');
    }


    public function update(User $user): bool
    {
        return $user->can('Update Hak Akses');
    }


    public function delete(User $user): bool
    {
        return $user->can('Hapus Hak Akses');
    }
}
