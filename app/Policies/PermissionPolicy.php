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
        return $user->can('Lihat Menu Permission');
    }


    public function create(User $user): bool
    {
        return $user->can('Tambah Data Permission');
    }


    public function update(User $user): bool
    {
        return $user->can('Edit Data Permission');
    }


    public function delete(User $user): bool
    {
        return $user->can('Hapus Data Permission');
    }
}
