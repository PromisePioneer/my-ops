<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\AccountCategory;
use App\Models\Company;
use Illuminate\Database\Seeder;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {

        $companyId = Company::where('code', '003')->first()->id;


        $asetLancar = AccountCategory::where('name', 'Aset Lancar')->first()->id;
        $asetTetap = AccountCategory::where('name', 'Aset Tetap')->first()->id;
        $utangLancar = AccountCategory::where('name', 'Utang Lancar')->first()->id;
        $utangJangkaPanjang = AccountCategory::where('name', 'Utang Jangka Panjang')->first()->id;
        $modal = AccountCategory::where('name', 'Modal')->first()->id;


        //linkkita
        $this->kasAccount($asetLancar, $companyId);
        $this->persediaan($asetLancar, $companyId);
        $this->piutangUsaha($asetLancar, $companyId);
        $this->biayaDibayarDimuka($asetLancar, $companyId);
        $this->pajakDibayarDimuka($companyId);
        $this->tanah($asetTetap, $companyId);
        $this->bangunan($asetTetap, $companyId);
        $this->kendaraan($asetTetap, $companyId);
        $this->mesin($asetTetap, $companyId);
        $this->peralatanInventarisKantor($asetTetap, $companyId);
        $this->peralatanInventarisJaringan($asetTetap, $companyId);
        $this->akumulasiPenyusutanAsetTetap($asetTetap, $companyId);
        $this->utangUsaha($utangLancar, $companyId);
        $this->utangDepositAlat($utangLancar, $companyId);
        $this->utangPajak($utangLancar, $companyId);
        $this->pendapatanDiterimaDimuka($utangLancar, $companyId);
        $this->biayaYangMasihHarusDibayar($utangLancar, $companyId);
        $this->utangLancarLainnya($utangLancar, $companyId);
        $this->utangBank($utangJangkaPanjang, $companyId);
        $this->utangKendaraan($utangJangkaPanjang, $companyId);
        $this->utangJangkaPanjangLainnya($utangJangkaPanjang, $companyId);
        $this->modal($companyId);
        $this->labaDitahan($modal, $companyId);
        $this->labaRugiBersihPeriodeBerjalan($modal, $companyId);
        $this->dividen($companyId);
        $this->pendapatanJasaLayananInternet($companyId);
        $this->pendapatanJasaLayananJaringanTelkom($companyId);
        $this->pendapatanLainnya($companyId);
        $this->bebanPokokPendapatan($companyId);
        $this->bebanPenjualan($companyId);
        $this->bebanKaryawan($companyId);
        $this->bebanUtilitas($companyId);
        $this->bebanSuppliesKantor($companyId);
        $this->bebangAngkutKirim($companyId);
        $this->bebanPerjalananDinas($companyId);
        $this->bebanTransportasiKendaraanAtauMesin($companyId);
        $this->bebanPemeliharaanAset($companyId);
        $this->bebanSewa($companyId);
        $this->bebanLainLain($companyId);
        $this->bebanPenyusutan($companyId);
        $this->bebanBunga($companyId);
        $this->bebanPajakPenghasilan($companyId);
    }

    private function kasAccount($asetLancar, $companyId): void
    {
        $parentAccount = Account::create([
            'code' => '111',
            'name' => 'Kas dan Setara Kas',
            'trial_balance_type' => 'debit',
            'category_id' => $asetLancar,
            'company_id' => $companyId,
        ]);

        Account::create([
            'name' => 'Kas Tunai',
            'code' => $parentAccount->code . '-' . '01',
            'parent_id' => $parentAccount->id,
            'trial_balance_type' => 'debit',
        ]);

        Account::create([
            'name' => 'Peti Brankas',
            'code' => $parentAccount->code . '-' . '02',
            'parent_id' => $parentAccount->id,
            'trial_balance_type' => 'debit',
        ]);

        Account::create([
            'name' => 'Rek. Mandiri 1720004378305',
            'code' => $parentAccount->code . '-' . '03',
            'parent_id' => $parentAccount->id,
            'trial_balance_type' => 'debit',
        ]);

        Account::create([
            'name' => 'Rek. BRK Syariah 1040801781',
            'code' => $parentAccount->code . '-' . '04',
            'parent_id' => $parentAccount->id,
            'trial_balance_type' => 'debit',
        ]);

    }

    private function persediaan(mixed $asetLancar, $companyId): void
    {
        $parentAccount = Account::create([
            'code' => '112',
            'name' => 'Persediaan',
            'trial_balance_type' => 'debit',
            'category_id' => $asetLancar,
            'company_id' => $companyId,
        ]);


        Account::create([
            'name' => 'Persediaan Perlengkapan Jaringan',
            'code' => $parentAccount->code . '-' . '01',
            'parent_id' => $parentAccount->id,
            'trial_balance_type' => 'debit',

        ]);


        Account::create([
            'name' => 'Persediaan Aset',
            'code' => $parentAccount->code . '-' . '03',
            'parent_id' => $parentAccount->id,
            'trial_balance_type' => 'debit',

        ]);

        Account::create([
            'name' => 'Persediaan Lainnya',
            'code' => $parentAccount->code . '-' . '03',
            'parent_id' => $parentAccount->id,
            'trial_balance_type' => 'debit',

        ]);
    }

    private function piutangUsaha(mixed $asetLancar, $companyId): void
    {
        $parentAccount = Account::create([
            'code' => '113',
            'name' => 'Piutang Usaha',
            'trial_balance_type' => 'debit',
            'category_id' => $asetLancar,
            'company_id' => $companyId,
        ]);

        Account::create([
            'name' => 'Piutang Pelanggan',
            'code' => $parentAccount->code . '-' . '01',
            'parent_id' => $parentAccount->id,
            'trial_balance_type' => 'debit',

        ]);

        Account::create([
            'name' => 'Piutang Karyawan',
            'code' => $parentAccount->code . '-' . '02',
            'parent_id' => $parentAccount->id,
            'trial_balance_type' => 'debit',

        ]);

        Account::create([
            'name' => 'Piutang Lainnya',
            'code' => $parentAccount->code . '-' . '03',
            'parent_id' => $parentAccount->id,
            'trial_balance_type' => 'debit',

        ]);

    }

    private function biayaDibayarDimuka(mixed $asetLancar, $companyId): void
    {
        $parentAccount = Account::create([
            'code' => '114',
            'name' => 'Biaya dibayar dimuka',
            'trial_balance_type' => 'debit',
            'category_id' => $asetLancar,
            'company_id' => $companyId,
        ]);


        Account::create([
            'name' => 'Sewa dibayar dimuka',
            'code' => $parentAccount->code . '-' . '01',
            'parent_id' => $parentAccount->id,
            'trial_balance_type' => 'debit',

        ]);

        Account::create([
            'name' => 'Biaya dibayar dimuka lainnya',
            'code' => $parentAccount->code . '-' . '02',
            'parent_id' => $parentAccount->id,
            'trial_balance_type' => 'debit',

        ]);


    }

    private function pajakDibayarDimuka($companyId): void
    {
        $parentAccount = Account::create([
            'code' => '115',
            'name' => 'Pajak dibayar dimuka',
            'trial_balance_type' => 'debit',
            'category_id' => 1,
            'company_id' => $companyId,
        ]);

        Account::create([
            'name' => 'PPn Masukan',
            'code' => $parentAccount->code . '-' . '01',
            'parent_id' => $parentAccount->id,
            'trial_balance_type' => 'debit',

        ]);

        Account::create([
            'name' => 'Kredit PPh 23',
            'code' => $parentAccount->code . '-' . '02',
            'parent_id' => $parentAccount->id,
            'trial_balance_type' => 'debit',

        ]);

        Account::create([
            'name' => 'Kredit Angsuran PPh 25',
            'code' => $parentAccount->code . '-' . '03',
            'parent_id' => $parentAccount->id,
            'trial_balance_type' => 'debit',

        ]);


    }

    private function tanah($asetTetap, $companyId): void
    {
        Account::create([
            'code' => '121',
            'name' => 'Tanah',
            'trial_balance_type' => 'debit',
            'category_id' => $asetTetap,
            'company_id' => $companyId,
        ]);
    }

    private function bangunan($asetTetap, $companyId): void
    {
        Account::create([
            'code' => '122',
            'name' => 'Bangunan',
            'trial_balance_type' => 'debit',
            'category_id' => $asetTetap,
            'company_id' => $companyId
        ]);
    }

    private function kendaraan($asetTetap, $companyId): void
    {
        Account::create([
            'code' => '123',
            'name' => 'Kendaraan',
            'trial_balance_type' => 'debit',
            'category_id' => $asetTetap,
            'company_id' => $companyId,
        ]);
    }

    private function mesin($asetTetap, $companyId): void
    {
        Account::create([
            'code' => '124',
            'name' => 'Mesin',
            'trial_balance_type' => 'debit',
            'category_id' => $asetTetap,
            'company_id' => $companyId,
        ]);
    }

    private function peralatanInventarisKantor($asetTetap, $companyId): void
    {
        Account::create([
            'code' => '125',
            'name' => 'Peralatan & Inventaris Kantor',
            'trial_balance_type' => 'debit',
            'category_id' => $asetTetap,
            'company_id' => $companyId,
        ]);
    }

    private function peralatanInventarisJaringan($asetTetap, $companyId): void
    {
        Account::create([
            'code' => '126',
            'name' => 'Peralatan & Inventaris Jaringan',
            'trial_balance_type' => 'debit',
            'category_id' => $asetTetap,
            'company_id' => $companyId,
        ]);
    }

    private function akumulasiPenyusutanAsetTetap($asetTetap, $companyId): void
    {
        Account::create([
            'code' => '130',
            'name' => 'Akumulasi Penyusutan Aset Tetap',
            'trial_balance_type' => 'credit',
            'category_id' => $asetTetap,
            'company_id' => $companyId,
        ]);
    }

    private function utangUsaha($utangLancar, $companyId): void
    {
        Account::create([
            'code' => '211',
            'name' => 'Utang Usaha',
            'trial_balance_type' => 'credit',
            'category_id' => $utangLancar,
            'company_id' => $companyId,
        ]);
    }

    private function utangDepositAlat($utangLancar, $companyId): void
    {
        Account::create([
            'code' => '212',
            'name' => 'Utang Deposit Alat',
            'trial_balance_type' => 'credit',
            'category_id' => $utangLancar,
            'company_id' => $companyId,
        ]);
    }

    private function utangPajak($utangLancar, $companyId): void
    {
        Account::create([
            'code' => '213',
            'name' => 'Utang Pajak',
            'trial_balance_type' => 'credit',
            'category_id' => $utangLancar,
            'company_id' => $companyId,
        ]);
    }

    private function pendapatanDiterimaDimuka(mixed $utangLancar, $companyId): void
    {
        Account::create([
            'code' => '214',
            'name' => 'Pendapatan Diterima Dimuka',
            'trial_balance_type' => 'credit',
            'category_id' => $utangLancar,
            'company_id' => $companyId,
        ]);
    }

    private function biayaYangMasihHarusDibayar(mixed $utangLancar, $companyId): void
    {
        $parentAccount = Account::create([
            'code' => '215',
            'name' => 'Biaya yang masih harus dibayar',
            'trial_balance_type' => 'credit',
            'category_id' => $utangLancar,
            'company_id' => $companyId,
        ]);


        Account::create([
            'name' => 'Utang Gaji',
            'code' => $parentAccount->code . '-' . '01',
            'parent_id' => $parentAccount->id,
            'trial_balance_type' => 'credit',

        ]);


        Account::create([
            'name' => 'BHP Telekomunikasi',
            'code' => $parentAccount->code . '-' . '02',
            'parent_id' => $parentAccount->id,
            'trial_balance_type' => 'credit',

        ]);

        Account::create([
            'name' => 'Kontribusi KPU/USO',
            'code' => $parentAccount->code . '-' . '03',
            'parent_id' => $parentAccount->id,
            'trial_balance_type' => 'credit',

        ]);
    }

    private function utangLancarLainnya(mixed $utangLancar, $companyId): void
    {
        Account::create([
            'code' => '216',
            'name' => 'Utang lancar lainnya',
            'trial_balance_type' => 'credit',
            'category_id' => $utangLancar,
            'company_id' => $companyId,
        ]);

    }

    private function utangBank(mixed $utangJangkaPanjang, $companyId): void
    {
        Account::create([
            'code' => '216',
            'name' => 'Utang lancar lainnya',
            'trial_balance_type' => 'credit',
            'category_id' => $utangJangkaPanjang,
            'company_id' => $companyId,
        ]);

    }

    private function utangKendaraan(mixed $utangJangkaPanjang, $companyId): void
    {
        Account::create([
            'code' => '222',
            'name' => 'Utang Kendaraan',
            'trial_balance_type' => 'credit',
            'category_id' => $utangJangkaPanjang,
            'company_id' => $companyId,
        ]);
    }

    private function utangJangkaPanjangLainnya(mixed $utangJangkaPanjang, $companyId): void
    {
        Account::create([
            'code' => '223',
            'name' => 'Utang Jangka panjang lainnya',
            'trial_balance_type' => 'credit',
            'category_id' => $utangJangkaPanjang,
            'company_id' => $companyId,
        ]);
    }

    private function modal($companyId): void
    {
        Account::create([
            'code' => '300',
            'name' => 'Modal',
            'trial_balance_type' => 'credit',
            'company_id' => $companyId,
        ]);
    }

    private function labaDitahan($modal, $companyId): void
    {
        Account::create([
            'code' => '312',
            'name' => 'Laba Ditahan / (Akumulasi Defisit)',
            'trial_balance_type' => 'credit',
            'category_id' => $modal,
            'company_id' => $companyId,
        ]);
    }

    private function labaRugiBersihPeriodeBerjalan($modal, $companyId): void
    {
        Account::create([
            'code' => '313',
            'name' => ' Laba / (Rugi) Bersih Periode Berjalan',
            'trial_balance_type' => 'credit',
            'category_id' => $modal,
            'company_id' => $companyId,
        ]);
    }

    private function dividen($companyId): void
    {
        Account::create([
            'code' => '320',
            'name' => 'Dividen',
            'trial_balance_type' => 'debit',
            'company_id' => $companyId,
        ]);
    }

    private function pendapatanJasaLayananInternet($companyId): void
    {
        $parentAccount = Account::create([
            'code' => '401',
            'name' => 'Pendapatan Jasa Layanan Internet',
            'company_id' => $companyId,
            'trial_balance_type' => 'debit',
        ]);


        Account::create([
            'name' => 'Pendapatan Jasa Layanan Internet (Retail)',
            'code' => $parentAccount->code . '-' . '01',
            'parent_id' => $parentAccount->id,
            'trial_balance_type' => 'debit',

        ]);

        Account::create([
            'name' => 'Pendapatan Jasa Layanan Internet (Corporate)',
            'code' => $parentAccount->code . '-' . '02',
            'parent_id' => $parentAccount->id,
            'trial_balance_type' => 'debit',

        ]);


    }

    private function pendapatanJasaLayananJaringanTelkom($companyId): void
    {

        $parentAccount = Account::create([
            'code' => '402',
            'name' => 'Pendapatan Jasa Layanan Jaringan Telekomunikasi',
            'company_id' => $companyId,
            'trial_balance_type' => 'debit',
        ]);

        Account::create([
            'name' => 'Pendapatan Jasa Layanan JarTel (Jartaplok)',
            'code' => $parentAccount->code . '-' . '01',
            'parent_id' => $parentAccount->id,
            'trial_balance_type' => 'debit',

        ]);

        Account::create([
            'name' => ' Pendapatan Jasa Layanan JarTel (Jartup)',
            'code' => $parentAccount->code . '-' . '02',
            'parent_id' => $parentAccount->id,
            'trial_balance_type' => 'debit',

        ]);
    }

    private function pendapatanLainnya($companyId): void
    {
        $parentAccount = Account::create([
            'code' => '403',
            'name' => 'Pendapatan Lainnya',
            'company_id' => $companyId,
            'trial_balance_type' => 'debit',
        ]);


        Account::create([
            'name' => 'Pendapatan Administrasi Pendaftaran',
            'code' => $parentAccount->code . '-' . '01',
            'parent_id' => $parentAccount->id,
            'trial_balance_type' => 'debit',
        ]);

        Account::create([
            'name' => 'Pendapatan Dari Penjualan Alat/Perangkat',
            'code' => $parentAccount->code . '-' . '02',
            'parent_id' => $parentAccount->id,
            'trial_balance_type' => 'debit',
        ]);

        Account::create([
            'name' => 'Pendapatan Bunga Bank',
            'code' => $parentAccount->code . '-' . '03',
            'parent_id' => $parentAccount->id,
            'trial_balance_type' => 'debit',
        ]);

        Account::create([
            'name' => 'Pendapatan/Penjualan Lainnya',
            'code' => $parentAccount->code . '-' . '04',
            'parent_id' => $parentAccount->id,
            'trial_balance_type' => 'debit',
        ]);


    }

    private function bebanPokokPendapatan($companyId): void
    {
        $parentAccount = Account::create([
            'code' => '500',
            'name' => 'Beban Pokok Pendapatan',
            'trial_balance_type' => 'debit',
            'company_id' => $companyId,
        ]);


        Account::create([
            'name' => 'Beban Uplink (IP Transit)',
            'code' => $parentAccount->code . '-' . '01',
            'parent_id' => $parentAccount->id,
            'trial_balance_type' => 'debit',

        ]);

        Account::create([
            'name' => 'Beban Interkoneksi (Metro-Net)',
            'code' => $parentAccount->code . '-' . '02',
            'parent_id' => $parentAccount->id,
            'trial_balance_type' => 'debit',

        ]);

        Account::create([
            'name' => 'Beban Pokok Lainnya',
            'code' => $parentAccount->code . '-' . '03',
            'parent_id' => $parentAccount->id,
            'trial_balance_type' => 'debit',

        ]);


    }

    private function bebanPenjualan($companyId): void
    {
        $parentAccount = Account::create([
            'code' => '501',
            'name' => 'Beban Penjualan',
            'trial_balance_type' => 'debit',
            'company_id' => $companyId,
        ]);


        Account::create([
            'name' => 'Beban Perlengkapan Jaringan',
            'code' => $parentAccount->code . '-' . '01',
            'parent_id' => $parentAccount->id,
            'trial_balance_type' => 'debit',

        ]);

        Account::create([
            'name' => 'Beban Jasa Maintenance Jaringan',
            'code' => $parentAccount->code . '-' . '02',
            'parent_id' => $parentAccount->id,
            'trial_balance_type' => 'debit',

        ]);
        Account::create([
            'name' => ' Beban Promosi & Pemasaran',
            'code' => $parentAccount->code . '-' . '03',
            'parent_id' => $parentAccount->id,
            'trial_balance_type' => 'debit',

        ]);

        Account::create([
            'name' => 'Beban atas Restitusi Jaringan',
            'code' => $parentAccount->code . '-' . '04',
            'parent_id' => $parentAccount->id,
            'trial_balance_type' => 'debit',

        ]);

        Account::create([
            'name' => 'Beban BHP Telekomunikasi & Kontribusi USO',
            'code' => $parentAccount->code . '-' . '04',
            'parent_id' => $parentAccount->id,
            'trial_balance_type' => 'debit',

        ]);
    }

    private function bebanKaryawan($companyId): void
    {
        $parentAccount = Account::create([
            'code' => '502',
            'name' => 'Beban Karyawan',
            'trial_balance_type' => 'debit',
            'company_id' => $companyId,
        ]);


        Account::create([
            'name' => 'Beban Gaji Karyawan',
            'code' => $parentAccount->code . '-' . '01',
            'parent_id' => $parentAccount->id,
            'trial_balance_type' => 'debit',

        ]);

        Account::create([
            'name' => 'Beban BPJS Ketenagakerjaan',
            'code' => $parentAccount->code . '-' . '02',
            'parent_id' => $parentAccount->id,
            'trial_balance_type' => 'debit',

        ]);

        Account::create([
            'name' => 'Beban BPJS Kesehatan',
            'code' => $parentAccount->code . '-' . '03',
            'parent_id' => $parentAccount->id,
            'trial_balance_type' => 'debit',

        ]);
    }

    private function bebanUtilitas($companyId): void
    {
        $parentAccount = Account::create([
            'code' => '503',
            'name' => 'Beban Utilitas',
            'trial_balance_type' => 'debit',
            'company_id' => $companyId,
        ]);


        Account::create([
            'name' => 'Beban Listrik',
            'code' => $parentAccount->code . '-' . '01',
            'parent_id' => $parentAccount->id,
            'trial_balance_type' => 'debit',
        ]);

        Account::create([
            'name' => 'Beban Telpon',
            'code' => $parentAccount->code . '-' . '02',
            'parent_id' => $parentAccount->id,
            'trial_balance_type' => 'debit',
        ]);

        Account::create([
            'name' => 'Beban Air',
            'code' => $parentAccount->code . '-' . '03',
            'parent_id' => $parentAccount->id,
            'trial_balance_type' => 'debit',
        ]);

        Account::create([
            'name' => 'Beban Gas',
            'code' => $parentAccount->code . '-' . '04',
            'parent_id' => $parentAccount->id,
            'trial_balance_type' => 'debit',
        ]);

    }

    private function bebanSuppliesKantor($companyId): void
    {
        $parentAccount = Account::create([
            'code' => '504',
            'name' => 'Beban Supplies Kantor',
            'trial_balance_type' => 'debit',
            'company_id' => $companyId,
        ]);


        Account::create([
            'name' => 'Beban Perlengkapan Kantor',
            'code' => $parentAccount->code . '-' . '01',
            'parent_id' => $parentAccount->id,
            'trial_balance_type' => 'debit',


        ]);

        Account::create([
            'name' => 'Beban Perlengkapan Lainnya',
            'code' => $parentAccount->code . '-' . '02',
            'parent_id' => $parentAccount->id,
            'trial_balance_type' => 'debit',


        ]);

    }

    private function bebangAngkutKirim($companyId): void
    {
        $parentAccount = Account::create([
            'code' => '505',
            'name' => 'Beban Angkut/Kirim',
            'trial_balance_type' => 'debit',
            'company_id' => $companyId,
        ]);


        Account::create([
            'name' => ' Beban Angkut Barang',
            'code' => $parentAccount->code . '-' . '01',
            'parent_id' => $parentAccount->id,
            'trial_balance_type' => 'debit',


        ]);

        Account::create([
            'name' => ' Beban Kirim Dokumen',
            'code' => $parentAccount->code . '-' . '02',
            'parent_id' => $parentAccount->id,
            'trial_balance_type' => 'debit',


        ]);

        Account::create([
            'name' => ' Beban Angkut/Kirim Lainnya',
            'code' => $parentAccount->code . '-' . '03',
            'parent_id' => $parentAccount->id,
            'trial_balance_type' => 'debit',


        ]);


    }

    private function bebanPerjalananDinas($companyId): void
    {
        $parentAccount = Account::create([
            'code' => '506',
            'name' => 'Beban Perjalanan Dinas',
            'trial_balance_type' => 'debit',
            'company_id' => $companyId,
        ]);

        Account::create([
            'name' => ' Beban Transportasi',
            'code' => $parentAccount->code . '-' . '01',
            'parent_id' => $parentAccount->id,
            'trial_balance_type' => 'debit',


        ]);

        Account::create([
            'name' => ' Beban Penginapan',
            'code' => $parentAccount->code . '-' . '02',
            'parent_id' => $parentAccount->id,
            'trial_balance_type' => 'debit',


        ]);

        Account::create([
            'name' => 'Beban Perjalanan Dinas Lainnya',
            'code' => $parentAccount->code . '-' . '03',
            'parent_id' => $parentAccount->id,
            'trial_balance_type' => 'debit',


        ]);


    }

    private function bebanTransportasiKendaraanAtauMesin($companyId): void
    {
        $parentAccount = Account::create([
            'code' => '507',
            'name' => 'Beban Transportasi Kendaraan/Mesin',
            'trial_balance_type' => 'debit',
            'company_id' => $companyId,
        ]);


        Account::create([
            'name' => ' BBM Kendaraan R4 Kantor',
            'code' => $parentAccount->code . '-' . '01',
            'parent_id' => $parentAccount->id,
            'trial_balance_type' => 'debit',


        ]);
        Account::create([
            'name' => 'BBM Kendaraan R4 Kantor Operasional Lapangan',
            'code' => $parentAccount->code . '-' . '02',
            'parent_id' => $parentAccount->id,
            'trial_balance_type' => 'debit',


        ]);
        Account::create([
            'name' => 'BBM Kendaraan R3 Kantor Operasional Lapangan',
            'code' => $parentAccount->code . '-' . '03',
            'parent_id' => $parentAccount->id,
            'trial_balance_type' => 'debit',


        ]);
        Account::create([
            'name' => 'BBM Kendaraan R2 Operasional',
            'code' => $parentAccount->code . '-' . '04',
            'parent_id' => $parentAccount->id,
            'trial_balance_type' => 'debit',


        ]);
        Account::create([
            'name' => 'Beban Mesin Genset',
            'code' => $parentAccount->code . '-' . '05',
            'parent_id' => $parentAccount->id,
            'trial_balance_type' => 'debit',


        ]);

    }

    private function bebanPemeliharaanAset($companyId): void
    {
        $parentAccount = Account::create([
            'code' => '508',
            'name' => 'Beban Pemeliharaan Aset',
            'trial_balance_type' => 'debit',
            'company_id' => $companyId,
        ]);


        Account::create([
            'name' => ' Beban Pemeliharaan Bangunan',
            'code' => $parentAccount->code . '-' . '01',
            'parent_id' => $parentAccount->id,
            'trial_balance_type' => 'debit',


        ]);

        Account::create([
            'name' => 'Beban Pemeliharaan Kendaraan',
            'code' => $parentAccount->code . '-' . '02',
            'parent_id' => $parentAccount->id,
            'trial_balance_type' => 'debit',


        ]);

        Account::create([
            'name' => 'Beban Pemeliharaan Mesin',
            'code' => $parentAccount->code . '-' . '03',
            'parent_id' => $parentAccount->id,
            'trial_balance_type' => 'debit',


        ]);

        Account::create([
            'name' => 'Beban Pemeliharaan Aset Kantor',
            'code' => $parentAccount->code . '-' . '04',
            'parent_id' => $parentAccount->id,
            'trial_balance_type' => 'debit',


        ]);

        Account::create([
            'name' => 'Beban Pemeliharaan Jaringan',
            'code' => $parentAccount->code . '-' . '05',
            'parent_id' => $parentAccount->id,
            'trial_balance_type' => 'debit',


        ]);


    }

    private function bebanSewa($companyId): void
    {
        Account::create([
            'code' => '510',
            'name' => 'Beban Sewa',
            'trial_balance_type' => 'debit',
            'company_id' => $companyId,
        ]);

    }

    private function bebanLainLain($companyId): void
    {

        $parentAccount = Account::create([
            'code' => '511',
            'name' => 'Beban Lain-lain',
            'trial_balance_type' => 'debit',
            'company_id' => $companyId,
        ]);
        Account::create([
            'name' => 'Retribusi',
            'code' => $parentAccount->code . '-' . '01',
            'parent_id' => $parentAccount->id,
            'trial_balance_type' => 'debit',
        ]);

        Account::create([
            'name' => 'Beban Pelatihan & Pengembangan SDM',
            'code' => $parentAccount->code . '-' . '02',
            'parent_id' => $parentAccount->id,
            'trial_balance_type' => 'debit',

        ]);

        Account::create([
            'name' => 'Beban Entertainment',
            'code' => $parentAccount->code . '-' . '03',
            'parent_id' => $parentAccount->id,
            'trial_balance_type' => 'debit',


        ]);

        Account::create([
            'name' => 'Beban Diluar Usaha Lainnya',
            'code' => $parentAccount->code . '-' . '04',
            'parent_id' => $parentAccount->id,
            'trial_balance_type' => 'debit',


        ]);
    }

    private function bebanPenyusutan($companyId): void
    {
        Account::create([
            'code' => '512',
            'name' => 'Beban Penyusutan',
            'trial_balance_type' => 'debit',
            'company_id' => $companyId,
        ]);
    }

    private function bebanBunga($companyId): void
    {
        Account::create([
            'code' => '513',
            'name' => 'Beban Bunga',
            'trial_balance_type' => 'debit',
            'company_id' => $companyId,
        ]);
    }

    private function bebanPajakPenghasilan($companyId): void
    {
        Account::create([
            'code' => '514',
            'name' => 'Beban Pajak Penghasilan',
            'trial_balance_type' => 'debit',
            'company_id' => $companyId,
        ]);
    }
}
