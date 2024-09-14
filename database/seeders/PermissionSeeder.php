<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $branchManagerRole = Role::where('name', 'Branch Manager')->first();

        $branches = [
            'Lihat Cabang',
            'Tambah Cabang',
            'Update Cabang',
            'Hapus Cabang',
            'Import Cabang',
        ];


        foreach ($branches as $permission) {
            Permission::create(['name' => $permission]);
        }


        $contact = [
            'Lihat Kontak',
            'Tambah Kontak',
            'Update Kontak',
            'Hapus Kontak',
        ];

        foreach ($contact as $permission) {
            Permission::create(['name' => $permission]);
        }

        $department = [
            'Lihat Departemen',
            'Tambah Departemen',
            'Update Departemen',
            'Hapus Departemen',
        ];

        foreach ($department as $permission) {
            Permission::create(['name' => $permission]);
        }

        $product = [
            'Lihat Produk',
            'Tambah Produk',
            'Update Produk',
            'Hapus Produk',
        ];

        foreach ($product as $permission) {
            Permission::create(['name' => $permission]);
        }

        $serviceCategories = [
            'Lihat Kategori Layanan',
            'Tambah Kategori Layanan',
            'Update Kategori Layanan',
            'Hapus Kategori Layanan',
        ];

        foreach ($serviceCategories as $permission) {
            Permission::create(['name' => $permission]);
        }

        $subAccount = [
            'Lihat Sub Akun',
            'Tambah Sub Akun',
            'Update Sub Akun',
            'Hapus Sub Akun',
        ];

        foreach ($subAccount as $permission) {
            Permission::create(['name' => $permission]);
        }

        $account = [
            'Lihat Akun',
            'Tambah Akun',
            'Update Akun',
            'Hapus Akun',
            'import Akun',
        ];

        foreach ($account as $permission) {
            Permission::create(['name' => $permission]);
        }

        $accountTransaction = [
            'Lihat Transaksi Akun',
            'Tambah Transaksi Akun',
            'Update Transaksi Akun',
            'Hapus Transaksi Akun',
        ];

        foreach ($accountTransaction as $permission) {
            Permission::create(['name' => $permission]);
        }

        $offeringLetter = [
            'Lihat Penawaran',
            'Tambah Penawaran',
            'Update Penawaran',
            'Hapus Penawaran',
        ];

        foreach ($offeringLetter as $permission) {
            Permission::create(['name' => $permission]);
        }

        $companyProfile = [
            'Lihat Profil Perusahaan',
            'Update Profil Perusahaan',
            'Hapus Profil Perusahaan',
        ];

        foreach ($companyProfile as $permission) {
            Permission::create(['name' => $permission]);
        }

        $roles = [
            'Lihat Jabatan',
            'Tambah Jabatan',
            'Update Jabatan',
            'Hapus Jabatan',
        ];

        foreach ($roles as $permission) {
            Permission::create(['name' => $permission]);
        }

        $permissions = [
            'Lihat Hak Akses',
            'Tambah Hak Akses',
            'Update Hak Akses',
            'Hapus Hak Akses',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        $sp = [
            'Lihat Surat Peringatan',
            'Update semua Surat Peringatan',
            'Update Surat Peringatan Sendiri',
            'Hapus Semua Surat Peringatan',
            'Hapus Surat Peringatan Sendiri',
        ];

        foreach ($sp as $permission) {
            Permission::create(['name' => $permission]);
        }


        $nationalHoliday = [
            'Lihat Libur Nasional',
            'Tambah Libur Nasional',
        ];


        foreach ($nationalHoliday as $permission) {
            Permission::create(['name' => $permission]);
        }


        $user = [
            'Lihat Karyawan',
            'Tambah Karyawan',
            'Update Karyawan',
            'Hapus Karyawan',
            'Aktifkan Karyawan',
        ];

        foreach ($user as $permission) {
            Permission::create(['name' => $permission]);
        }


        $leaves = [
            'Lihat Manajemen Cuti',
            'Acc Cuti',
            'Lihat Detail Cuti',
        ];

        foreach ($leaves as $permission) {
            Permission::create(['name' => $permission]);
        }
    }
}
