<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AccountPolicy
{
    use HandlesAuthorization;

    public function view(User $user): bool
    {
        return $user->can('Lihat Menu Akun');
    }

    public function create(User $user): bool
    {
        return $user->can('Tambah Data Akun');
    }

    public function update(User $user): bool
    {
        return $user->can('Edit Data Akun');
    }

    public function delete(User $user): bool
    {
        return $user->can('Hapus Data Akun');
    }


    public function filterByCompany(User $user): bool
    {
        return $user->can('Filter Data Akun Berdasarkan Perusahaan');
    }
}
