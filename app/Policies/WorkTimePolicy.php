<?php

namespace App\Policies;

use App\Models\User;

class WorkTimePolicy
{
    public function view(User $user): bool
    {
        return $user->can('Lihat Menu Jam Kerja');
    }

    public function create(User $user): bool
    {
        return $user->can('Tambah Data Jam Kerja');
    }
    public function destroy(User $user): bool
    {
        return $user->can('Hapus Data Jam Kerja');
    }

    public function setGlobalDefaultWorkTime(User $user): bool
    {
        return $user->can('Set Jam Kerja Bawaan');
    }
}
