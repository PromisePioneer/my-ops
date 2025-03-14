<?php

namespace App\Policies;

use App\Models\User;

class AttendanceManualRequestPolicy
{

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user): bool
    {
        return $user->can('Lihat Menu Permintaan Absensi Manual');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('Tambah Data Permintaan Absensi Manual');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user): bool
    {
        return $user->can('Ubah Data Permintaan Absensi Manual');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user): bool
    {
        return $user->can('Hapus Data Permintaan Absensi Manual');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function confirm(User $user): bool
    {
        return $user->can('Konfirmasi Data Permintaan Absensi Manual');
    }
}
