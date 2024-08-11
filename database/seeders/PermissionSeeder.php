<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $kacabRole = Role::where('name', 'Kepala Cabang')->firstOrFail();
        $accountant = Role::where('name', 'Accountant')->firstOrFail();
        $directorRole = Role::where('name', 'Direktur')->firstOrFail();
        $financeManagerRole = Role::where('name', 'Manager Keuangan')->firstOrFail();

        $branches = [
            'lihat cabang',
            'tambah cabang',
            'update cabang',
            'hapus cabang',
        ];

        foreach ($branches as $permission) {
            Permission::create(['name' => $permission]);
            $financeManagerRole->givePermissionTo($permission);
            $directorRole->givePermissionTo($permission);
        }

        $beginningBalances = [
            'lihat saldo awal',
            'tambah saldo awal',
            'update saldo awal',
            'hapus saldo awal',
        ];

        foreach ($beginningBalances as $permission) {
            Permission::create(['name' => $permission]);
            $financeManagerRole->givePermissionTo($permission);
        }

        $contact = [
            'lihat contact',
            'tambah contact',
            'update contact',
            'hapus contact',
        ];

        foreach ($contact as $permission) {
            Permission::create(['name' => $permission]);
            $kacabRole->givePermissionTo($permission);
            $directorRole->givePermissionTo($permission);
            $financeManagerRole->givePermissionTo($permission);
            $accountant->givePermissionTo($permission);
        }

        $product = [
            'lihat produk',
            'tambah produk',
            'update produk',
            'hapus produk',
        ];

        foreach ($product as $permission) {
            Permission::create(['name' => $permission]);
            $kacabRole->givePermissionTo($permission);
            $directorRole->givePermissionTo($permission);
            $financeManagerRole->givePermissionTo($permission);
            $accountant->givePermissionTo($permission);
        }

        $serviceCategories = [
            'lihat kategori layanan',
            'tambah kategori layanan',
            'update kategori layanan',
            'hapus kategori layanan',
        ];

        foreach ($serviceCategories as $permission) {
            Permission::create(['name' => $permission]);

            $kacabRole->givePermissionTo($permission);
            $directorRole->givePermissionTo($permission);
            $financeManagerRole->givePermissionTo($permission);
            $accountant->givePermissionTo($permission);
        }

        $subAccount = [
            'lihat sub akun',
            'tambah sub akun',
            'update sub akun',
            'hapus sub akun',
        ];

        foreach ($subAccount as $permission) {
            Permission::create(['name' => $permission]);
            $financeManagerRole->givePermissionTo($permission);
        }

        $account = [
            'lihat akun',
            'tambah akun',
            'update akun',
            'hapus akun',
            'import akun',
        ];

        foreach ($account as $permission) {
            Permission::create(['name' => $permission]);
            $financeManagerRole->givePermissionTo($permission);
        }

        $accountTransaction = [
            'lihat transaksi akun',
            'tambah transaksi akun',
            'update transaksi akun',
            'hapus transaksi akun',
        ];

        foreach ($accountTransaction as $permission) {
            Permission::create(['name' => $permission]);
            $financeManagerRole->givePermissionTo($permission);
        }

        $offeringLetter = [
            'lihat penawaran',
            'tambah penawaran',
            'update penawaran',
            'hapus penawaran',
        ];

        foreach ($offeringLetter as $permission) {
            Permission::create(['name' => $permission]);
            $financeManagerRole->givePermissionTo($permission);
            $accountant->givePermissionTo($permission);
            $directorRole->givePermissionTo($permission);
            $kacabRole->givePermissionTo($permission);
        }

        $companyProfile = [
            'lihat profil perusahaan',
            'update profil perusahaan',
            'hapus profil perusahaan',
        ];

        foreach ($companyProfile as $permission) {
            Permission::create(['name' => $permission]);

            $kacabRole->givePermissionTo($permission);
            $financeManagerRole->givePermissionTo($permission);
            $directorRole->givePermissionTo($permission);
        }

        $user = [
            'lihat user',
            'tambah user',
            'update user',
            'hapus user',
        ];

        foreach ($user as $permission) {
            Permission::create(['name' => $permission]);
            $kacabRole->givePermissionTo($permission);
            $directorRole->givePermissionTo($permission);
            $financeManagerRole->givePermissionTo($permission);
        }

        $roles = [
            'lihat role',
            'tambah role',
            'update role',
            'hapus role',
        ];

        foreach ($roles as $permission) {
            Permission::create(['name' => $permission]);
            $kacabRole->givePermissionTo($permission);
            $directorRole->givePermissionTo($permission);
            $financeManagerRole->givePermissionTo($permission);
        }

        $permissions = [
            'lihat permission',
            'tambah permission',
            'update permission',
            'hapus permission',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);

            $kacabRole->givePermissionTo($permission);
            $directorRole->givePermissionTo($permission);
            $financeManagerRole->givePermissionTo($permission);
        }
    }
}
