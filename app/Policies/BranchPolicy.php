<?php

namespace App\Policies;

use App\Models\User;

class BranchPolicy
{
    public function __construct()
    {
        //
    }

    public function view(User $user): bool
    {
        return $user->can('Lihat Cabang');
    }

    public function create(User $user): bool
    {
        return $user->can('Tambah Cabang');
    }

    public function update(User $user): bool
    {
        return $user->can('Update Cabang');
    }

    public function delete(User $user): bool
    {
        return $user->can('Hapus Cabang');
    }

    public function import(User $user): bool
    {
        return $user->can('Import Cabang');
    }
}
