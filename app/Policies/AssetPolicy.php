<?php

namespace App\Policies;

use App\Models\User;

class AssetPolicy
{
    public function view(User $user): bool
    {
        return $user->can('Lihat Menu Aset');
    }

    public function create(User $user): bool
    {
        return $user->can('Tambah Data Aset');
    }

    public function update(User $user): bool
    {
        return $user->can('Edit Data Aset');
    }

    public function delete(User $user): bool
    {
        return $user->can('Hapus Data Aset');
    }

    public function import(User $user): bool
    {
        return $user->can('Import Data Aset');
    }

    public function confirm(User $user): bool
    {
        return $user->can('Konfirmasi Data Aset');
    }

    public function filterByBranch(User $user): bool
    {
        return $user->can('Filter Data Aset Berdasarkan Cabang');
    }

    public function viewDetail(User $user): bool
    {
        return $user->can('Lihat Detail Aset');
    }
}
