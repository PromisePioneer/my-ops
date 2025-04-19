<?php

namespace App\Policies;

use App\Models\OfferingLetter;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class OfferingLetterPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('Lihat Menu Penawaran');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, OfferingLetter $offeringLetter): bool
    {
        return $user->hasPermissionTo('Lihat Data Penawaran');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('Tambah Data Penawaran');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user): bool
    {
        return $user->hasPermissionTo('Edit Data Penawaran');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user): bool
    {
        return $user->hasPermissionTo('Hapus Data Penawaran');
    }


    public function viewDetail(User $user): bool
    {
        return $user->hasPermissionTo('Lihat Detail Data Penawaran');
    }

    public function print(User $user): bool
    {
        return $user->hasPermissionTo('Print Data Penawaran');
    }

    public function confirm(User $user): bool
    {
        return $user->hasPermissionTo('Konfirmasi Data Penawaran');
    }


}
