<?php

namespace App\Policies;

use App\Models\Fab;
use App\Models\OfferingLetter;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class FabPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('Lihat Menu Fab');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user): bool
    {
        return $user->can('Lihat Data Fab');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('Tambah Data Fab');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user): bool
    {
        return $user->can('Edit Data Fab');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user): bool
    {
        return $user->can('Hapus Data Fab');
    }


    public function viewDetail(User $user): bool
    {
        return $user->can('Lihat Detail Data Fab');
    }

    public function print(User $user): bool
    {
        return $user->can('Print Data Fab');
    }


    public function confirm(User $user): bool
    {
        return $user->can('Konfirmasi Data Fab');
    }

    public function printContract(User $user): bool
    {
        return $user->can('Print Kontrak');
    }
}
