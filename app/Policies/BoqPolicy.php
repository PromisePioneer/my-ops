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
        return $user->can('Lihat BoQ');
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
        if ($boq->submitter === $user->id) {
            return true;
        }

        return $user->can('Mengubah BoQ');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Boq $boq): bool
    {
        return $user->can('Menghapus BoQ');
    }
}
