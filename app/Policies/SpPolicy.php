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
        return $user->can('Lihat Surat Peringatan');
    }


    public function create(User $user): bool
    {
        return $user->can('Tambah Surat Peringatan');
    }


    public function update(User $user, SP $sp): bool
    {
        if ($user->can('Update semua Surat Peringatan')) {
            return true;
        }

        if ($user->can('Update Surat Peringatan Sendiri')) {
            return $user->id === $sp->user_id;
        }

        return false;
    }


    public function delete(User $user, SP $sp): bool
    {
        return $user->can('Hapus Surat Peringatan');
    }

}
