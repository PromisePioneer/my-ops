<?php

namespace App\Policies;

use App\Models\User;

class EmployeeSchedulePolicy
{

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user): bool
    {
        return $user->can('Lihat Menu Pengaturan Jadwal Libur');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('Tambah / Ubah Jadwal Libur');
    }


    public function filterByRole(User $user): bool
    {
        return $user->can('Filter Data Jadwal Libur Berdasarkan Jabatan');
    }


    public function filterByBranch(User $user): bool
    {
        return $user->can('Filter Data Jadwal Libur Berdasarkan Cabang');
    }
}
