<?php

namespace App\Policies;

use App\Models\User;

class AttendanceSummaryPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user): bool
    {
        return $user->can('Lihat Menu Riwayat Absensi');
    }


    public function viewAnyData(User $user): bool
    {
        return $user->can('Lihat Semua Data Riwayat Absensi');
    }


    public function viewOnlyBranchData(User $user): bool
    {
        return $user->can('Lihat Data Riwayat Absensi Cabang Sendiri');
    }

}
