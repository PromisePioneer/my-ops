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
        return $user->can('Lihat Menu Kontak');
    }

    public function create(User $user): bool
    {
        return $user->can('Tambah Data Kontak');
    }

    public function update(User $user): bool
    {
        return $user->can('Edit Data Kontak');
    }

    public function delete(User $user): bool
    {
        return $user->can('Hapus Data Kontak');
    }


    public function viewArchives(User $user): bool
    {
        return $user->can('Lihat Data Arsip Kontak');
    }
}
