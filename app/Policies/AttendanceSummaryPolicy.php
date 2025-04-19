<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AttendanceSummaryPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user): bool
    {
        return $user->can('Lihat Menu Riwayat Absensi');
    }

    public function viewDetail(User $user): bool
    {
        if ($user->can('Lihat Detail Riwayat Absensi')) {
            return true;
        }
    }


    public function filterBranch(User $user): bool
    {
        return $user->can('');
    }


}
