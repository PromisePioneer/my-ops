<?php

namespace App\Policies;

use App\Models\User;

class PurchaseOrderPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('Lihat Menu PO');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user): bool
    {
        return $user->can('Lihat Data PO');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('Tambah Data PO');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user): bool
    {
        return $user->can('Edit Data PO');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user): bool
    {
        return $user->can('Hapus Data PO');
    }


    public function viewDetail(User $user): bool
    {
        return $user->can('Lihat Detail PO');
    }

    public function confirm(User $user): bool
    {
        return $user->can('Konfirmasi Data PO');
    }

    public function print(User $user): bool
    {
        return $user->can('Print Data PO');
    }


}
