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

        // general master data
        $this->branch();
        $this->contact();
        $this->skl();
        $this->product();
        $this->serviceCategory();
        $this->department();
        $this->employeePosition();
        $this->broadbandPacket();
        $this->companyData();
        $this->area();
        $this->unitType();


        //accounting master data
        $this->account();
        $this->initialBalance();
        $this->taxSetting();
        $this->assetData();


        //operational master data
        $this->supplier();
        $this->goodsCategory();
        $this->joinClosureCode();

        // Inventory Controller
        $this->BoQ();

        // journal
        $this->generalJournal();
        $this->trialBalances();
        $this->generalLedger();


        //income-transaction
        $this->offeringLetter();
        $this->fab();
        $this->po();
        $this->baa();
        $this->bast();


        //expenses
        $this->expenditure();
        $this->invoiceExpenses();


        //utility
        $this->companyProfile();


        //payroll
        $this->payrollSetting();


        //allowance
        $this->positionAllowance();
        $this->mealAllowance();
        $this->transportationAllowance();
        $this->overtimeAllowance();
        $this->religiousHolidayAllowance();


        //deduction
        $this->sladeduction();
        $this->ninePastFiveteenDeduction();
        $this->additionalDeduction();

        //benefit
        $this->bonusSales();
        $this->bonusProject();
        $this->additionalBonus();


        //employee management
        $this->employeeData();
        $this->employeePermissions();
        $this->leaveAndPermission();
        $this->sp();
        $this->employeeContract();
        $this->sk();


        //adms
        $this->nationalHoliday();
        $this->fpDevice();
        $this->workTime();
        $this->employeeSchedule();
        $this->attendancesSummary();
    }


    public function attendancesSummary(): void
    {
        $permissions = [
            'Lihat Menu Riyawat Absensi',
            'Lihat Detail Riyawat Absensi',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }

    public function employeeSchedule(): void
    {
        $permissions = [
            'Lihat Menu Jadwal Libur Karyawan',
            'Tambah / Ubah Data Jadwal Libur Karyawan',
            'Filter Jadwal Libur Karyawan',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }

    public function workTime(): void
    {
        $permissions = [
            'Lihat Menu Pengaturan Jam Kerja',
            'Tambah Data Pengaturan Jam Kerja',
            'Edit Data Pengaturan Jam Kerja',
            'Hapus Data Pengaturan Jam Kerja',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }


    public function fpDevice(): void
    {
        $permissions = [
            'Lihat Menu Mesin Absen',
            'Tambah Menu Mesin Absen',
            'Edit Menu Mesin Absen',
            'Hapus Menu Mesin Absen',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }


    public function nationalHoliday(): void
    {
        $permissions = [
            'Lihat Menu Hari Libur Nasional',
            'Generate Data Hari Libur Nasional',
        ];


        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }

    public function sk(): void
    {
        $permissions = [
            'Lihat Menu SK',
            'Tambah Data SK',
            'Edit Data SK',
            'Print Data SK',
            'Hapus Data SK',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }


    public function employeeContract(): void
    {
        $permissions = [
            'Lihat Menu Kontrak Karyawan',
            'Filter Data Kontrak Karyawan Berdasarkan Cabang',
            'Filter Data Kontrak Karyawan Berdasarkan Tahun Dan Bulan',
            'Perpanjang Data Kontrak Karyawan',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }


    public function sp(): void
    {
        $permissions = [
            'Lihat Menu SP',
            'Tambah Data SP',
            'Edit Data SP',
            'Hapus Data SP',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }

    public function leaveAndPermission(): void
    {
        $permissions = [
            'Lihat Menu Manajemen Cuti',
            'Tambah Data Manajemen Cuti',
            'Edit Data Manajemen Cuti',
            'Hapus Data Manajemen Cuti',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }

    public function employeePermissions(): void
    {
        $permissions = [
            'Lihat Menu Permission',
            'Tambah Data Permission',
            'Edit Data Permission',
            'Hapus Data Permission',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }

    public function employeeData(): void
    {
        $permissions = [
            'Lihat Menu Data Karyawan',
            'Filter Data Karyawan Berdasarkan Cabang',
            'Filter Data Karyawan Berdasarkan Perusahaan',
            'Filter Data Karyawan Berdasarkan Tahun',
            'Filter Data Karyawan Berdasarkan Bulan',
            'Filter Data Karyawan Berdasarkan Aktif Dan Tidak Aktif',
            'Tambah Data Karyawan',
            'Edit Data Karyawan',
            'Hapus Data Karyawan',
            'Aktifasi Data Karyawan',
            'Lihat Detail Data Karyawan'
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }

    public function additionalBonus(): void
    {
        $permissions = [
            'Lihat Menu Bonus Lainnya',
            'Tambah Menu Bonus Lainnya',
            'Edit Menu Bonus Lainnya',
            'Hapus Menu Bonus Lainnya',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }

    public function bonusProject(): void
    {
        $permissions = [
            'Lihat Menu Bonus Project',
            'Tambah Data Bonus Project',
            'Edit Data Bonus Project',
            'Hapus Data Bonus Project',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }

    public function bonusSales(): void
    {
        $permissions = [
            'Lihat Menu Bonus Sales',
            'Tambah Data Bonus Sales',
            'Edit Data Bonus Sales',
            'Hapus Data Bonus Sales',
            'Import Data Bonus Sales',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }

    public function additionalDeduction(): void
    {
        $permissions = [
            'Lihat Menu Denda Lainnya',
            'Tambah Data Denda Lainnya',
            'Edit Data Denda Lainnya',
            'Hapus Data Denda Lainnya',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }

    public function ninePastFiveteenDeduction(): void
    {
        $permissions = [
            'Lihat Menu Denda 9.15',
            'Tambah Data Denda 9.15',
            'Edit Data Denda 9.15',
            'Hapus Data Denda 9.15',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }

    public function slaDeduction(): void
    {
        $permissions = [
            'Lihat Menu Denda SLA',
            'Tambah Data Denda SLA',
            'Edit Data Denda SLA',
            'Hapus Data Denda SLA',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }


    public function religiousHolidayAllowance(): void
    {
        $permissions = [
            'Lihat Menu THR',
            'Tambah Data THR',
            'Edit Data THR',
            'Hapus Data THR',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }

    public function overtimeAllowance(): void
    {
        $permissions = [
            'Lihat Menu Tunjangan Lembur',
            'Tambah Data Tunjangan Lembur',
            'Edit Data Tunjangan Lembur',
            'Hapus Data Tunjangan Lembur',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }


    public function transportationAllowance(): void
    {
        $permissions = [
            'Lihat Menu Tunjangan Transportasi',
            'Tambah Data Tunjangan Transportasi',
            'Edit Data Tunjangan Transportasi',
            'Hapus Data Tunjangan Transportasi',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }

    public function mealAllowance(): void
    {
        $permissions = [
            'Lihat Menu Tunjangan Makanan',
            'Tambah Tunjangan Makanan',
            'Edit Tunjangan Makanan',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }

    public function positionAllowance(): void
    {
        $permissions = [
            'Lihat Menu Tunjangan Jabatan',
            'Tambah Tunjangan Jabatan',
            'Edit Tunjangan Jabatan',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }

    public function payrollSetting(): void
    {
        $permissions = [
            'Lihat Menu Payroll',
            'Lihat Periode Payroll',
            'Lihat Payroll Component',
            'Lihat BPJS',
            'Lihat CutOff Payroll'
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }

    public function companyProfile(): void
    {
        $permissions = [
        'Lihat Menu Profil Perusahaan',
            'Update Profile Perusahaan',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }

    public function invoiceExpenses(): void
    {
        $permissions = [
            'Lihat Menu Invoice Pengeluaran'
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }

    public function expenditure(): void
    {
        $permissions = [
            'Lihat Menu Pengeluaran',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }

    public function invoice(): void
    {
        $permissions = [
            'Lihat Menu Invoice',
            'Tambah Data Invoice',
            'Edit Data Invoice',
            'Hapus Data Invoice',
            'Konfirmasi Data Invoice',
            'Print Data Invoice',
            'Tambah PPH 21 Invoice'
        ];


        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }

    public function bast(): void
    {
        $permissions = [
            'Lihat Menu Bast',
            'Tambah Data Bast',
            'Edit Data Bast',
            'Lihat Detail Bast',
            'Konfirmasi Data Bast',
            'Print Data Bast',
            'Hapus Data Bast',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }


    public function generalLedger(): void
    {
        $permissions = [
            'Lihat Menu Buku Besar',
            'Lihat Daftar Akun Buku Besar',
            'Lihat Detail Buku Besar',
            'Filter Buku Besar',
            'Filter Buku Besar Berdasarkan Bulan',
            'Filter Buku Besar Berdasarkan Tahun',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }

    public function trialBalances(): void
    {
        $permissions = [
            'Lihat Menu Neraca Saldo',
            'Filter Neraca Saldo Berdasarkan Cabang',
            'Filter Neraca Saldo Berdasarkan Tahun',
            'Filter Neraca Saldo Berdasarkan Bulan',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }

    public function generalJournal(): void
    {
        $permissions = [
            'Lihat Menu Jurnal Umum',
            'Filter Jurnal Umum Berdasarkan Cabang',
            'Filter Jurnal Umum Berdasarkan Tahun',
            'FIlter Jurnal Umum Berdasarkan Bulan',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }


    public function supplier(): void
    {
        $permissions = [
            'Lihat Menu Supplier',
            'Tambah Data Supplier',
            'Edit Data Supplier',
            'Hapus Data Supplier',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }


    public function joinClosureCode(): void
    {
        $permissions = [
            'Lihat Menu Kode Joint Closure',
            'Tambah Data Kode Joint Closure',
            'Edit Data Kode Joint Closure',
            'Hapus Data Kode Joint Closure',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }

    public function goodsCategory()
    {
        $permissions = [
            'Lihat Menu Kategori Barang',
            'Tambah Data Kategori Barang',
            'Edit Data Kategori Barang',
            'Hapus Data Kategori Barang',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }


    public function assetData(): void
    {
        $permissions = [
            'Lihat Menu Aset',
            'Tambah Data Aset',
            'Lihat Detail Data Aset',
            'Hapus Data Aset',
            'Konfirmasi Data Aset'
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }

    public function taxSetting(): void
    {
        $permissions = [
            'Lihat Menu Pengaturan Pajak',
            'Tambah Data Pengaturan Pajak',
            'Ubah Data Pengaturan Pajak',
            'Hapus Data Pengaturan Pajak',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }

    public function initialBalance(): void
    {
        $permissions = [
            'Lihat Menu Saldo Awal',
            'Filter Menu Saldo Awal',
            'Tambah Data Saldo Awal',
            'Edit Data Saldo Awal',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }

    public function account(): void
    {
        $permissions = [
            'Lihat Menu Akun',
            'Tambah Data Akun',
            'Edit Data Akun',
            'Hapus Data Akun',
        ];


        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }

    public function area(): void
    {
        $permissions = [
            'Lihat Menu Area',
            'Tambah Data Area',
            'Edit Data Area',
            'Hapus Data Area',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }

    public function companyData(): void
    {
        $permissions = [
            'Lihat Menu Data Perusahaan',
            'Tambah Data Perusahaan',
            'Edit Data Perusahaan',
            'Hapus Data Perusahaan',
        ];


        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }

    public function broadbandPacket(): void
    {
        $permissions = [
            'Lihat Menu Paket Broadband',
            'Tambah Data Paket Broadband',
            'Edit Data Paket Broadband',
            'Hapus Data Paket Broadband',
        ];


        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }


    public function employeePosition(): void
    {
        $permissions = [
            'Lihat Menu Jabatan',
            'Tambah Data Jabatan',
            'Edit Data Jabatan',
            'Hapus Data Jabatan',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }

    public function department(): void
    {
        $permissions = [
            'Lihat Menu Departemen',
            'Tambah Data Departemen',
            'Edit Data Departemen',
            'Hapus Data Departemen',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }

    public function serviceCategory(): void
    {
        $permissions = [
            'Lihat Menu Kategori Layanan',
            'Tambah Data Kategori Layanan',
            'Edit Data Kategori Layanan',
            'Hapus Data Kategori Layanan',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }

    public function product(): void
    {
        $permissions = [
            'Lihat Menu Produk',
            'Tambah Data Produk',
            'Edit Data Produk',
            'Hapus Data Produk',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }

    public function unitType(): void
    {
        $permissions = [
            'Lihat Menu Satuan',
            'Tambah Data Satuan',
            'Edit Data Satuan',
            'Hapus Data Satuan',
        ];


        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }

    public function skl(): void
    {
        $permissions = [
            'Lihat Menu SKL',
            'Tambah Menu SKL',
            'Edit Menu SKL',
            'Hapus Menu SKL',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }


    public function baa(): void
    {
        $permissions = [
            'Lihat Menu BAA',
            'Lihat Data BAA',
            'Tambah Data BAA',
            'Edit Data BAA',
            'Hapus Data BAA',
            'Lihat Detail Data BAA',
            'Konfirmasi BAA',
            'Print Data BAA',
            'Buat SPK / Ubah SPK',
            'Print SPK'
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
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
        $permissions = [
            'Lihat Menu Cabang',
            'Tambah Data Cabang',
            'Edit Data Cabang',
            'Hapus Data Cabang',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }


    public function contact(): void
    {

        $contacts = [
            'Lihat Menu Kontak',
            'Tambah Data Kontak',
            'Edit Data Kontak',
            'Hapus Data Kontak',
        ];
        foreach ($contacts as $contact) {
            Permission::create(['name' => $contact]);
        }
    }


    public function BoQ(): void
    {
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
    }
}
