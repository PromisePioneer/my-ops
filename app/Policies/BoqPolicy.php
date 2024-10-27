<?php

namespace App\Policies;

use App\Models\Boq;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class BoqPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Boq $boq): bool
    {
        return $user->can('Lihat Menu BoQ');
    }


    public function viewAllBoQ(User $user): bool
    {
        return $user->can('Lihat Semua Data BoQ');
    }


    public function viewBoQonSameBranch(User $user, Boq $boq): bool
    {
        if ($user->can('Lihat Data BoQ Sesuai Cabang Masing2')) {
            return $user->branch_id === $boq->branch_id;
        }

        return false;
    }


    public function viewOwnBoQ(User $user, Boq $boq): bool
    {
        if ($user->can('Lihat Pengajuan BoQ Pribadi')) {
            return $user->id === $boq->submitter_id;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('Mengajukan BoQ');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Boq $boq): bool
    {
        if ($user->can('Mengubah BoQ Pribadi')) {
            return $boq->submitter_id === $user->id;
        }

        return true;
    }

    public function approveBoQ(User $user, Boq $boq): bool
    {
        return $user->can('Menyetujui BoQ', $boq);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function deleteBoQ(User $user, Boq $boq): bool
    {
        return $user->can('Menghapus BoQ');
    }
}
