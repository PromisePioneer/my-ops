<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function view(User $user): bool
    {
        return $user->can('Lihat Menu Data Karyawan');
    }

    public function create(User $user): bool
    {
        return $user->can('Tambah Data Karyawan');
    }

    public function update(User $user): bool
    {
        return $user->can('Edit Data Karyawan');
    }

    public function delete(User $user): bool
    {
        return $user->can('Hapus Data Karyawan');
    }


    public function viewArchived(User $user): bool
    {
        return $user->can('Lihat Menu Arsip Karyawan');
    }

    public function viewDetail(User $user): bool
    {
        return $user->can('Lihat Detail Data Karyawan');
    }

    public function import(User $user): bool
    {
        return $user->can('Import Data Karyawan');
    }


    public function filterBasedOnBranch(User $user): bool
    {
        return $user->can('Filter Data Karyawan Berdasarkan Cabang');
    }

    public function filterBasedOnCompany(User $user): bool
    {
        return $user->can('Filter Data Karyawan Berdasarkan Perusahaan');
    }


    public function filterBasedOnYear(User $user): bool
    {
        return $user->can('Filter Data Karyawan Berdasarkan Tahun');
    }

    public function filterBasedOnMonth(User $user): bool
    {
        return $user->can('Filter Data Karyawan Berdasarkan Bulan');
    }

}
