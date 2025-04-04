<?php

namespace App\Policies;

use App\Models\User;

class TransactionPolicy
{
    public function view(User $user): bool
    {
        return $user->can('Lihat Menu Transaksi');
    }

    public function create(User $user): bool
    {
        return $user->can('Tambah Data Transaksi');
    }

    public function update(User $user): bool
    {
        return $user->can('Ubah Data Transaksi');
    }

    public function delete(User $user): bool
    {
        return $user->can('Hapus Data Transaksi');
    }

    public function confirm(User $user): bool
    {
        return $user->can('Konfirmasi Data Transaksi');
    }


    public function finalApprove(User $user)
    {
        return $user->can('Final Approve Data Transaksi');
    }
}
