<?php

namespace App\Policies;

use App\Models\AttendancesSummary;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AttendanceSummaryPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user): bool
    {
        return $user->can('Lihat Riwayat Absensi');
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
