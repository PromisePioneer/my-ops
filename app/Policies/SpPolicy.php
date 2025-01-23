<?php

namespace App\Policies;

use App\Models\SP;
use App\Models\User;

class SpPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
    }

    public function view(User $user): bool
    {
        return $user->can('Lihat Menu SP');
    }


    public function create(User $user): bool
    {
        return $user->can('Tambah Data SP');
    }


    public function update(User $user, SP $sp): bool
    {
        if ($user->can('Edit Data SP')) {
            return true;
        }

        return false;
    }


    public function delete(User $user, SP $sp): bool
    {
        return $user->can('Hapus Data SP');
    }

}
