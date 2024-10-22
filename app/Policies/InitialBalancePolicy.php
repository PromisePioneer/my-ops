<?php

namespace App\Policies;

use App\Models\AccountTransaction;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class InitialBalancePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAnyInitialBalance(User $user): bool
    {
        return $user->can('Lihat Semua Saldo Awal');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, AccountTransaction $accountTransaction): bool
    {
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, AccountTransaction $accountTransaction): bool
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, AccountTransaction $accountTransaction): bool
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, AccountTransaction $accountTransaction): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, AccountTransaction $accountTransaction): bool
    {
        //
    }
}
