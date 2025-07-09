<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\MenuSection;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->dashboard();


        $this->commonMasterData();
        $this->accountingMasterData();
        $this->operationalMasterData();

        $this->inventoryController();

        $this->journals();


        $this->transactions();


        $this->incomeTransactions();

        $this->utilities();

        $this->employeeManagement();
        $this->attendanceManagement();


    }

    private function dashboard(): void
    {
        Menu::create([
            'name' => 'Dashboard',
            'link' => 'home',
            'icon' => '<i class="ki-duotone ki-element-11 fs-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                            <span class="path4"></span>
                        </i>',
            'section_id' => MenuSection::where('name', 'Dashboard')->first()->id,
        ]);
    }

    private function commonMasterData(): void
    {

        $parentAccount = Menu::create([
            'name' => 'Master Umum',
            'icon' => '<i class="ki-duotone ki-element-7 fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>',
            'section_id' => MenuSection::where('name', 'Master Data')->first()->id,
        ]);

        Menu::create([
            'name' => 'Cabang',
            'link' => 'master/common/branch',
            'icon' => null,
            'parent_id' => $parentAccount->id,
        ]);


        Menu::create([
            'name' => 'Kontak',
            'link' => 'master/common/contact',
            'icon' => null,
            'parent_id' => $parentAccount->id,
        ]);

        Menu::create([
            'name' => 'Syarat Ketentuan Layanan',
            'link' => 'master/common/skl',
            'icon' => null,
            'parent_id' => $parentAccount->id,
        ]);


        Menu::create([
            'name' => 'Satuan',
            'link' => 'master/common/unit-types',
            'icon' => null,
            'parent_id' => $parentAccount->id,
        ]);


        Menu::create([
            'name' => 'Kategori Layanan',
            'link' => 'master/common/service-categories',
            'icon' => null,
            'parent_id' => $parentAccount->id,
        ]);


        Menu::create([
            'name' => 'Departemen',
            'link' => 'master/common/department',
            'icon' => null,
            'parent_id' => $parentAccount->id,
        ]);


        Menu::create([
            'name' => 'Jabatan',
            'link' => 'master/common/roles',
            'icon' => null,
            'parent_id' => $parentAccount->id,
        ]);


        Menu::create([
            'name' => 'Paket Broadband',
            'link' => 'master/common/broadband-packets',
            'icon' => null,
            'parent_id' => $parentAccount->id,
        ]);


        Menu::create([
            'name' => 'Data Perusahaan',
            'link' => 'master/common/companies',
            'icon' => null,
            'parent_id' => $parentAccount->id,
        ]);

        Menu::create([
            'name' => 'Area',
            'link' => '/master/common/area',
            'icon' => null,
            'parent_id' => $parentAccount->id,
        ]);
    }

    private function accountingMasterData(): void
    {
        $parentAccount = Menu::create([
            'name' => 'Master Keuangan',
            'icon' => '<i class="ki-duotone ki-element-7 fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>',
            'section_id' => MenuSection::where('name', 'Master Data')->first()->id
        ]);


        Menu::create([
            'name' => 'Kategori Akun',
            'link' => 'master/accounting/account-categories',
            'icon' => null,
            'parent_id' => $parentAccount->id,
        ]);

        Menu::create([
            'name' => 'Daftar Akun',
            'link' => 'master/accounting/accounts',
            'icon' => null,
            'parent_id' => $parentAccount->id,
        ]);

        Menu::create([
            'name' => 'Saldo Awal',
            'link' => 'master/accounting/initial-balances',
            'icon' => null,
            'parent_id' => $parentAccount->id,
        ]);

        Menu::create([
            'name' => 'Saldo Awal Persediaan',
            'link' => 'master/accounting/initial-inventory-balances',
            'icon' => null,
            'parent_id' => $parentAccount->id,
        ]);


        Menu::create([
            'name' => 'Pengaturan Pajak',
            'link' => 'master/accounting/tax-settings',
            'icon' => null,
            'parent_id' => $parentAccount->id,
        ]);


        Menu::create([
            'name' => 'Daftar Aset',
            'link' => 'master/accounting/assets',
            'icon' => null,
            'parent_id' => $parentAccount->id,
        ]);
    }

    private function operationalMasterData()
    {
        $parentAccount = Menu::create([
            'name' => 'Master Operasional',
            'icon' => '<i class="ki-duotone ki-element-7 fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>',
            'section_id' => MenuSection::where('name', 'Master Data')->first()->id
        ]);


        Menu::create([
            'name' => 'Kategori Barang',
            'link' => 'master/operational/item-categories',
            'icon' => null,
            'parent_id' => $parentAccount->id,
        ]);


        Menu::create([
            'name' => 'Daftar Barang',
            'link' => 'master/operational/items',
            'icon' => null,
            'parent_id' => $parentAccount->id,
        ]);

        Menu::create([
            'name' => 'Jam Kerja',
            'link' => '/master/operational/work-time',
            'icon' => null,
            'parent_id' => $parentAccount->id,
        ]);


    }

    private function inventoryController(): void
    {
        $parentAccount = Menu::create([
            'name' => 'Inventory Controller',
            'icon' => '<i class="ki-duotone ki-dollar fs-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                        </i>',
            'section_id' => MenuSection::where('name', 'Inventory')->first()->id,
        ]);

        Menu::create([
            'name' => 'Stok Barang',
            'link' => 'inventory/stocks',
            'icon' => null,
            'parent_id' => $parentAccount->id,
        ]);


        Menu::create([
            'name' => 'Pengkodean Barang',
            'link' => 'inventory/draft-stocks',
            'icon' => null,
            'parent_id' => $parentAccount->id,
        ]);

        Menu::create([
            'name' => 'Pemakaian Barang',
            'link' => 'inventory/stock-withdrawals',
            'icon' => null,
            'parent_id' => $parentAccount->id,
        ]);


        Menu::create([
            'name' => 'Mutasi Barang',
            'link' => 'inventory/stock-mutations',
            'icon' => null,
            'parent_id' => $parentAccount->id,
        ]);

    }

    private function journals(): void
    {
        $parentAccount = Menu::create([
            'name' => 'Penjurnalan',
            'icon' => '<i class="ki-duotone ki-book-square fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                            </i>',
            'parent_id' => null,
        ]);

        Menu::create([
            'name' => 'Jurnal Umum',
            'link' => 'journals/general-journal',
            'icon' => null,
            'parent_id' => $parentAccount->id,
        ]);


        Menu::create([
            'name' => 'Neraca Saldo',
            'link' => 'journals/trial-balance',
            'icon' => null,
            'parent_id' => $parentAccount->id,
        ]);

        Menu::create([
            'name' => 'Buku Besar',
            'link' => 'journals/general-ledger',
            'icon' => null,
            'parent_id' => $parentAccount->id,
        ]);


        Menu::create([
            'name' => 'Laporan Keuangan',
            'link' => 'journals/financial-report',
            'icon' => null,
            'parent_id' => $parentAccount->id,
        ]);
    }

    private function transactions(): void
    {
        Menu::create([
            'name' => 'Transaksi',
            'link' => 'transactions',
            'icon' => '<i class="ki-duotone ki-element-11 fs-2">
                          <span class="path1"></span>
                          <span class="path2"></span>
                          <span class="path3"></span>
                          <span class="path4"></span>
                       </i>',
            'section_id' => MenuSection::where('name', 'Transaksi')->first()->id,
        ]);
    }

    private function incomeTransactions(): void
    {
        $parentAccount = Menu::create([
            'name' => 'Pendapatan',
            'icon' => '<i class="ki-duotone ki-dollar fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                            </i>',
            'section_id' => MenuSection::where('name', 'Transaksi')->first()->id,
        ]);

        Menu::create([
            'name' => 'Penawaran',
            'link' => 'income-transactions/offering-letters',
            'icon' => null,
            'parent_id' => $parentAccount->id,
        ]);


        Menu::create([
            'name' => 'Purchase Order',
            'link' => 'income-transactions/po',
            'icon' => null,
            'parent_id' => $parentAccount->id,
        ]);

        Menu::create([
            'name' => 'Formulir Aplikasi Berlangganan',
            'link' => 'income-transactions/fab',
            'icon' => null,
            'parent_id' => $parentAccount->id,
        ]);


        Menu::create([
            'name' => 'Berita Acara Aktivasi',
            'link' => 'income-transactions/baa',
            'icon' => null,
            'parent_id' => $parentAccount->id,
        ]);

        Menu::create([
            'name' => 'Berita Acara Serah Terima',
            'link' => 'income-transactions/bast',
            'icon' => null,
            'parent_id' => $parentAccount->id,
        ]);


        Menu::create([
            'name' => 'Invoice',
            'link' => 'income-transactions/invoice',
            'icon' => null,
            'parent_id' => $parentAccount->id,
        ]);
    }

    private function utilities()
    {
        $parentAccount = Menu::create([
            'name' => 'Utilitas',
            'icon' => '<i class="ki-duotone ki-abstract-29 fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>',
            'section_id' => MenuSection::where('name', 'Utilitas')->first()->id,
        ]);


        Menu::create([
            'name' => 'Profil Perusahaan',
            'link' => 'utility/company-profile',
            'icon' => null,
            'parent_id' => $parentAccount->id,
        ]);

        Menu::create([
            'name' => 'Riwayat Aktifitas',
            'link' => 'utility/activity-log',
            'icon' => null,
            'parent_id' => $parentAccount->id,
        ]);


        Menu::create([
            'name' => 'Menu Management',
            'link' => 'utility/menus',
            'icon' => null,
            'parent_id' => $parentAccount->id,
        ]);
    }

    private function employeeManagement(): void
    {
        $parentAccount = Menu::create([
            'name' => 'Manajemen Karyawan',
            'icon' => '<i class="ki-duotone ki-abstract-29 fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>',
            'section_id' => MenuSection::where('name', 'Manajemen Karyawan')->first()->id,
        ]);


        Menu::create([
            'name' => 'Data Karyawan',
            'link' => 'manage-users/users',
            'icon' => null,
            'parent_id' => $parentAccount->id,
        ]);

        Menu::create([
            'name' => 'Hak Akses',
            'link' => 'manage-users/permissions',
            'icon' => null,
            'parent_id' => $parentAccount->id,
        ]);


        Menu::create([
            'name' => 'Manajemen Cuti',
            'link' => 'manage-users/leaves',
            'icon' => null,
            'parent_id' => $parentAccount->id,
        ]);

        Menu::create([
            'name' => 'Surat Peringatan',
            'link' => 'manage-users/sp',
            'icon' => null,
            'parent_id' => $parentAccount->id,
        ]);

        Menu::create([
            'name' => 'Kontrak Karyawan',
            'link' => 'manage-users/contract-management',
            'icon' => null,
            'parent_id' => $parentAccount->id,
        ]);

        Menu::create([
            'name' => 'SK Karyawan',
            'link' => 'manage-users/sk',
            'icon' => null,
            'parent_id' => $parentAccount->id,
        ]);


        Menu::create([
            'name' => 'Struktur Jabatan',
            'link' => 'manage-users/role-hierarchy',
            'icon' => null,
            'parent_id' => $parentAccount->id,
        ]);
    }

    private function attendanceManagement()
    {
        $parentAccount = Menu::create([
            'name' => 'Manajemen Absensi',
            'icon' => '<i class="ki-duotone ki-abstract-29 fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>',
            'section_id' => MenuSection::where('name', 'Manajemen Karyawan')->first()->id,
        ]);


        Menu::create([
            'name' => 'Libur Nasional',
            'link' => 'adms/national-holiday',
            'icon' => null,
            'parent_id' => $parentAccount->id,
        ]);

        Menu::create([
            'name' => 'Mesin Absensi',
            'link' => 'adms/fp-devices',
            'icon' => null,
            'parent_id' => $parentAccount->id,
        ]);


        Menu::create([
            'name' => 'Pengaturan Jam Kerja',
            'link' => 'adms/work-time-settings',
            'icon' => null,
            'parent_id' => $parentAccount->id,
        ]);

        Menu::create([
            'name' => 'Pengaturan Jadwal Libur',
            'link' => 'adms/employee-schedules',
            'icon' => null,
            'parent_id' => $parentAccount->id,
        ]);

        Menu::create([
            'name' => 'Riwayat Absensi',
            'link' => 'adms/attendances-summary',
            'icon' => null,
            'parent_id' => $parentAccount->id,
        ]);
    }
}
