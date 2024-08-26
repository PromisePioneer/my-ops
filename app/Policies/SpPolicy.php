<?php

namespace App\Policies;

class SpPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
    }


//    public function view(User $user): bool
//    {
//        return $user->can('lihat SP');
//    }
//
//
//    public function create(User $user): bool
//    {
//        return $user->can('tambah SP');
//    }
//
//
//    public function update(User $user, SP $sp): bool
//    {
//        if ($user->can('update semua SP')) {
//            return true;
//        }
//
//        if ($user->can('update SP Sendiri')) {
//            return $user->id === $sp->user_id;
//        }
//
//        return false;
//    }
//
//
//    public function delete(User $user, SP $sp): bool
//    {
//        if ($user->can('hapus SP')) {
//            return true;
//        }
//
//        return $user->can('hapus SP');
//    }


}
