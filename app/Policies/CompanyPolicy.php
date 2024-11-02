<?php

namespace App\Policies;

use App\Models\Company;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CompanyPolicy
{
    public function viewAny(User $user): bool
    {
        //
    }

    public function view(User $user): bool
    {
        return $user->can('Lihat Data Perusahaan');
    }

    public function create(User $user): bool
    {
        return $user->can('Tambah Data Perusahaan');
    }

    public function update(User $user): bool
    {
        return $user->can('Edit Data Perusahaan');
    }

    public function delete(User $user): bool
    {
        return $user->can('Hapus Data Perusahaan');
    }
}
