<?php

namespace App\Policies;

use App\Models\User;

class ContactPolicy
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
        return $user->can('Lihat Kontak');
    }

    public function create(User $user): bool
    {
        return $user->can('Tambah Kontak');
    }

    public function update(User $user): bool
    {
        return $user->can('Edit Kontak');
    }

    public function delete(User $user): bool
    {
        return $user->can('Hapus Kontak');
    }
}
