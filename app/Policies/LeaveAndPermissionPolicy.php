<?php

namespace App\Policies;

use App\Models\User;

class LeaveAndPermissionPolicy
{
    public function __construct()
    {
        //
    }

    public function view(User $user): bool
    {
        return $user->can('Lihat Menu Manajemen Cuti');
    }


    public function create(User $user): bool
    {
        return $user->can('Tambah Data Manajemen Cuti');
    }


    public function update(User $user): bool
    {
        return $user->can('Edit Data Manajemen Cuti');
    }


    public function delete(User $user): bool
    {
        return $user->can('Hapus Data Manajemen Cuti');
    }


    public function confirm(User $user): bool
    {
        return $user->can('Konfirmasi Data Manajemen Cuti');
    }

}
