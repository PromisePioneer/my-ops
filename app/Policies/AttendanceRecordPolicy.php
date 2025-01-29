<?php

namespace App\Policies;

use App\Models\User;

class AttendanceRecordPolicy
{
    public function view(User $user): bool
    {
        return $user->can('Lihat Menu Riwayat Absensi');
    }
}
