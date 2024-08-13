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
        return $user->can('lihat contact');
    }

    public function create(User $user): bool
    {
        return $user->can('tambah contact');
    }

    public function update(User $user): bool
    {
        return $user->can('update contact');
    }

    public function delete(User $user): bool
    {
        return $user->can('hapus contact');
    }
}
