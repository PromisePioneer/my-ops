<?php

namespace App\Policies;

use App\Models\User;

class BranchPolicy
{

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
}
