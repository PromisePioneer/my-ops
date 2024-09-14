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
        return $user->can('Lihat Manajemen Cuti');
    }


    public function viewDetail(User $user): bool
    {
        return $user->can('Lihat Detail Cuti');
    }

    public function changeStatus(User $user): bool
    {
        return $user->can('Acc Cuti');
    }
}
