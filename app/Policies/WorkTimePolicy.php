<?php

namespace App\Policies;

use App\Models\User;

class WorkTimePolicy
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
        if ($user->hasRole('HR')) {
            return $user->can('lihat semua shift');
        }

        if ($user->hasRole('Kepala Cabang')) {
            return $user->can('lihat shift berdasarkan cabang');
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->can('tambah shift');
    }

    public function edit(User $user): bool
    {
        return $user->can('update shift');
    }

    public function destroy(User $user): bool
    {
        return $user->can('hapus shift');
    }
}
