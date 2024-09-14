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
        return $user->can('Lihat Jam Kerja');
    }

    public function create(User $user): bool
    {
        return $user->can('Tambah Jam Kerja');
    }

    public function edit(User $user): bool
    {
        return $user->can('Update Jam Kerja');
    }

    public function destroy(User $user): bool
    {
        return $user->can('Hapus Jam Kerja');
    }


    public function viewDetail(User $user): bool
    {
        return $user->can('Lihat Jam Kerja Karyawan');
    }
}
