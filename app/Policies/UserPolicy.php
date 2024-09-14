<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{


    public function view(User $user): bool
    {
        return $user->can('Lihat Karyawan');
    }

    public function create(User $user): bool
    {
        return $user->can('Tambah Karyawan');
    }

    public function update(User $user): bool
    {
        return $user->can('Update Karyawan');
    }

    public function delete(User $user): bool
    {
        return $user->can('Hapus Karyawan');
    }


    public function setActive(User $user): bool
    {
        return $user->can('Aktifkan Karyawan');
    }


    public function viewDetail(User $user): bool
    {
        return $user->can('Lihat Detail Karyawan');
    }


    public function import(User $user): bool
    {
        return $user->can('Import Karyawan');
    }
}
