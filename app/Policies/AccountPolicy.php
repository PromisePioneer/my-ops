<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class AccountPolicy
{
    use HandlesAuthorization;

    public function __construct()
    {
        //
    }

    public function view(User $user): Response
    {
        return $user->can('Lihat Akun')
            ? Response::allow()
            : Response::deny('Anda tidak memiliki akses ke halaman ini');
    }

    public function create(User $user): bool
    {
        return $user->can('Tambah Akun');
    }

    public function update(User $user): bool
    {
        return $user->can('Update Akun');
    }

    public function delete(User $user): bool
    {
        return $user->can('Hapus Akun');
    }

    public function import(User $user): bool
    {
        return $user->can('Import Akun');
    }
}
