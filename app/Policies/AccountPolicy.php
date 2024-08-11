<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AccountPolicy
{

    use HandlesAuthorization;

    public function __construct()
    {
        //
    }

    public function view(User $user): bool
    {
        return $user->can('lihat akun');
    }

    public function create(User $user): bool
    {
        return $user->can('tambah akun');
    }

    public function update(User $user): bool
    {
        return $user->can('update akun');
    }


    public function delete(User $user): bool
    {
        return $user->can('hapus akun');
    }

    public function import(User $user): bool
    {
        return $user->can('import akun');
    }
}
