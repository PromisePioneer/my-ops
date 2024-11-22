<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();
        $this->BoQ();
        $this->branch();
        $this->fab();
        $this->po();
        $this->offeringLetter();
    }


    public function po(): void
    {
        $permissions = [
            'Lihat Menu PO',
            'Lihat Data PO',
            'Tambah Data PO',
            'Edit Data PO',
            'Lihat Detail PO',
            'Hapus Data PO',
            'Print Data PO',
            'Konfirmasi Data PO',
            'Print Data PO'
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }

    public function fab(): void
    {
        $permissions = [
            'Lihat Menu Fab',
            'Lihat Data Fab',
            'Tambah Data Fab',
            'Edit Data Fab',
            'Lihat Detail Fab',
            'Print Data Fab',
            'Hapus Data Fab',
            'Konfirmasi Data Fab',
            'Print Kontrak'
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

    }


    public function offeringLetter(): void
    {
        $permissions = [
            'Lihat Menu Penawaran',
            'Tambah Data Penawaran',
            'Edit Data Penawaran',
            'Lihat Detail Penawaran',
            'Print Data Penawaran',
            'Hapus Data Penawaran',
            'Konfirmasi Data Penawaran'
        ];


        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }

    public function branch(): void
    {
        $director = Role::where('name', 'Director')->first();
        $FAManager = Role::where('name', 'FA & Tax Manager')->first();
        $operationalManager = Role::where('name', 'Operational Manager')->first();
        $generalManager = Role::where('name', 'General Manager')->first();


        $permissions = [
            'Lihat Cabang',
            'Tambah Cabang',
            'Edit Cabang',
            'Hapus Cabang',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }


        $director->givePermissionTo(['Lihat Cabang', 'Tambah Cabang', 'Edit Cabang', 'Hapus Cabang']);
        $FAManager->givePermissionTo(['Lihat Cabang']);
        $operationalManager->givePermissionTo(['Lihat Cabang']);
        $generalManager->givePermissionTo(['Lihat Cabang']);
    }


    public function contact(): void
    {
        $director = Role::where('name', 'Director')->first();
        $FAManager = Role::where('name', 'FA & Tax Manager')->first();
        $operationalManager = Role::where('name', 'Operational Manager')->first();

        $contacts = [
            'Lihat Kontak',
            'Tambah Kontak',
            'Edit Kontak',
            'Hapus Kontak',
        ];
        foreach ($contacts as $contact) {
            Permission::create(['name' => $contact]);
        }

        $director->givePermissionTo([
            'Lihat Kontak',
            'Tambah Kontak',
            'Edit Kontak',
            'Hapus Kontak',
        ]);

        $FAManager->givePermissionTo([
            'Lihat Kontak',
            'Tambah Kontak',
            'Edit Kontak',
            'Hapus Kontak',
        ]);

        $operationalManager->givePermissionTo([
            'Lihat Kontak',
            'Tambah Kontak',
            'Edit Kontak',
            'Hapus Kontak',
        ]);

    }


    public function BoQ(): void
    {
        $director = Role::where('name', 'Director')->first();
        $FAManager = Role::where('name', 'FA & Tax Manager')->first();
        $operationalManager = Role::where('name', 'Operational Manager')->first();
        $branchManager = Role::where('name', 'Branch Manager')->first();
        $generalManager = Role::where('name', 'General Manager')->first();


        $boq = [
            'Lihat Menu BoQ',
            'Lihat Semua Data BoQ',
            'Lihat Data BoQ Sesuai Cabang Masing2',
            'Lihat Pengajuan BoQ Pribadi',
            'Mengajukan BoQ',
            'Mengubah BoQ Pribadi',
            'Menyetujui BoQ',
            'Mengetahui BoQ',
            'Menghapus BoQ',
        ];

        foreach ($boq as $permission) {
            Permission::create(['name' => $permission]);
        }

        $operationalManager->givePermissionTo(
            [
                'Lihat Menu BoQ',
                'Mengajukan BoQ',
                'Lihat Semua Data BoQ',
                'Mengubah BoQ Pribadi',
                'Menyetujui BoQ',
            ]
        );

        $director->givePermissionTo([
            'Lihat Menu BoQ',
            'Mengajukan BoQ',
            'Lihat Semua Data BoQ',
            'Mengubah BoQ Pribadi',
            'Mengetahui BoQ',
            'Menghapus BoQ',
        ]);

        $FAManager->givePermissionTo([
            'Lihat Menu BoQ',
            'Lihat Semua Data BoQ',
            'Mengajukan BoQ',
            'Mengubah BoQ Pribadi',
            'Menghapus BoQ',
        ]);


        $generalManager->givePermissionTo([
            'Lihat Menu BoQ',
            'Lihat Semua Data BoQ',
            'Mengajukan BoQ',
            'Mengetahui BoQ',
            'Menghapus BoQ',
        ]);

        $branchManager->givePermissionTo([
            'Lihat Menu BoQ',
            'Lihat Data BoQ Sesuai Cabang Masing2',
            'Mengajukan BoQ',
            'Mengubah BoQ Pribadi',
            'Menghapus BoQ',
        ]);
    }
}
