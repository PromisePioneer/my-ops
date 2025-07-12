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

        $linkkita = Company::where('code', '003')->first()->id;
        $asetLancar = AccountCategory::where('name', 'Aset Lancar')->first()->id;
        $asetTetap = AccountCategory::where('name', 'Aset Tetap')->first()->id;
        $utangLancar = AccountCategory::where('name', 'Utang Lancar')->first()->id;
        $utangJangkaPanjang = AccountCategory::where('name', 'Utang Jangka Panjang')->first()->id;
        $modal = AccountCategory::where('name', 'Modal')->first()->id;


        $this->kasAccount($asetLancar, $linkkita);
        $this->persediaan($asetLancar, $linkkita);
        $this->piutangUsaha($asetLancar, $linkkita);
        $this->biayaDibayarDimuka($asetLancar, $linkkita);
        $this->pajakDibayarDimuka($linkkita);
        $this->tanah($asetTetap, $linkkita);
        $this->bangunan($asetTetap, $linkkita);
        $this->kendaraan($asetTetap, $linkkita);
        $this->mesin($asetTetap, $linkkita);
        $this->peralatanInventarisKantor($asetTetap, $linkkita);
        $this->peralatanInventarisJaringan($asetTetap, $linkkita);
        $this->akumulasiPenyusutanAsetTetap($asetTetap, $linkkita);
        $this->utangUsaha($utangLancar, $linkkita);
        $this->utangDepositAlat($utangLancar, $linkkita);
        $this->utangPajak($utangLancar, $linkkita);
        $this->pendapatanDiterimaDimuka($utangLancar, $linkkita);
        $this->biayaYangMasihHarusDibayar($utangLancar, $linkkita);
        $this->utangLancarLainnya($utangLancar, $linkkita);
        $this->utangBank($utangJangkaPanjang, $linkkita);
        $this->utangKendaraan($utangJangkaPanjang, $linkkita);
        $this->utangJangkaPanjangLainnya($utangJangkaPanjang, $linkkita);
        $this->modal($linkkita);
        $this->labaDitahan($modal, $linkkita);
        $this->labaRugiBersihPeriodeBerjalan($modal, $linkkita);
        $this->dividen($linkkita);
        $this->pendapatanJasaLayananInternet($linkkita);
        $this->pendapatanJasaLayananJaringanTelkom($linkkita);
        $this->pendapatanLainnya($linkkita);
        $this->bebanPokokPendapatan($linkkita);
        $this->bebanPenjualan($linkkita);
        $this->bebanKaryawan($linkkita);
        $this->bebanUtilitas($linkkita);
        $this->bebanSuppliesKantor($linkkita);
        $this->bebangAngkutKirim($linkkita);
        $this->bebanPerjalananDinas($linkkita);
        $this->bebanTransportasiKendaraanAtauMesin($linkkita);
        $this->bebanPemeliharaanAset($linkkita);
        $this->bebanSewa($linkkita);
        $this->bebanLainLain($linkkita);
        $this->bebanPenyusutan($linkkita);
        $this->bebanBunga($linkkita);
        $this->bebanPajakPenghasilan($linkkita);
    }

    private function kasAccount($asetLancar, $linkkita): void
    {
        $parentAccount = Account::create([
            'code' => '111',
            'name' => 'Kas dan Setara Kas',
            'trial_balance_type' => 'debit',
            'category_id' => $asetLancar,
            'company_id' => $linkkita,
        ]);

        Account::create([
            'name' => 'Kas Tunai',
            'code' => $parentAccount->code . '-' . '01',
            'parent_id' => $parentAccount->id,
        ]);

        Account::create([
            'name' => 'Peti Brankas',
            'code' => $parentAccount->code . '-' . '02',
            'parent_id' => $parentAccount->id,
        ]);

        Account::create([
            'name' => 'Rek. Mandiri 1720004378305',
            'code' => $parentAccount->code . '-' . '03',
            'parent_id' => $parentAccount->id,
        ]);

        Account::create([
            'name' => 'Rek. BRK Syariah 1040801781',
            'code' => $parentAccount->code . '-' . '04',
            'parent_id' => $parentAccount->id,
        ]);

    }

    private function persediaan(mixed $asetLancar, $linkkita): void
    {
        $parentAccount = Account::create([
            'code' => '112',
            'name' => 'Persediaan',
            'trial_balance_type' => 'debit',
            'category_id' => $asetLancar,
            'company_id' => $linkkita,
        ]);


        Account::create([
            'name' => 'Persediaan Perlengkapan Jaringan',
            'code' => $parentAccount->code . '-' . '01',
            'parent_id' => $parentAccount->id,
        ]);


        Account::create([
            'name' => 'Persediaan Aset',
            'code' => $parentAccount->code . '-' . '03',
            'parent_id' => $parentAccount->id,
        ]);

        Account::create([
            'name' => 'Persediaan Lainnya',
            'code' => $parentAccount->code . '-' . '03',
            'parent_id' => $parentAccount->id,
        ]);
    }

    private function piutangUsaha(mixed $asetLancar, $linkkita): void
    {
        $parentAccount = Account::create([
            'code' => '113',
            'name' => 'Piutang Usaha',
            'trial_balance_type' => 'debit',
            'category_id' => $asetLancar,
            'company_id' => $linkkita,
        ]);

        Account::create([
            'name' => 'Piutang Pelanggan',
            'code' => $parentAccount->code . '-' . '01',
            'parent_id' => $parentAccount->id,
        ]);

        Account::create([
            'name' => 'Piutang Karyawan',
            'code' => $parentAccount->code . '-' . '02',
            'parent_id' => $parentAccount->id,
        ]);

        Account::create([
            'name' => 'Piutang Lainnya',
            'code' => $parentAccount->code . '-' . '03',
            'parent_id' => $parentAccount->id,
        ]);

    }

    private function biayaDibayarDimuka(mixed $asetLancar, $linkkita): void
    {
        $parentAccount = Account::create([
            'code' => '114',
            'name' => 'Biaya dibayar dimuka',
            'trial_balance_type' => 'debit',
            'category_id' => $asetLancar,
            'company_id' => $linkkita,
        ]);


        Account::create([
            'name' => 'Sewa dibayar dimuka',
            'code' => $parentAccount->code . '-' . '01',
            'parent_id' => $parentAccount->id,
        ]);

        Account::create([
            'name' => 'Biaya dibayar dimuka lainnya',
            'code' => $parentAccount->code . '-' . '02',
            'parent_id' => $parentAccount->id,
        ]);


    }

    private function pajakDibayarDimuka($linkkita): void
    {
        $parentAccount = Account::create([
            'code' => '115',
            'name' => 'Pajak dibayar dimuka',
            'trial_balance_type' => 'debit',
            'category_id' => 1,
            'company_id' => $linkkita,
        ]);

        Account::create([
            'name' => 'PPn Masukan',
            'code' => $parentAccount->code . '-' . '01',
            'parent_id' => $parentAccount->id,
        ]);

        Account::create([
            'name' => 'Kredit PPh 23',
            'code' => $parentAccount->code . '-' . '02',
            'parent_id' => $parentAccount->id,
        ]);

        Account::create([
            'name' => 'Kredit Angsuran PPh 25',
            'code' => $parentAccount->code . '-' . '03',
            'parent_id' => $parentAccount->id,
        ]);


    }

    private function tanah($asetTetap, $linkkita): void
    {
        Account::create([
            'code' => '121',
            'name' => 'Tanah',
            'trial_balance_type' => 'debit',
            'category_id' => $asetTetap,
            'company_id' => $linkkita,
        ]);
    }

    private function bangunan($asetTetap, $linkkita): void
    {
        Account::create([
            'code' => '122',
            'name' => 'Bangunan',
            'trial_balance_type' => 'debit',
            'category_id' => $asetTetap,
        ]);
    }

    private function kendaraan($asetTetap): void
    {
        Account::create([
            'code' => '123',
            'name' => 'Kendaraan',
            'trial_balance_type' => 'debit',
            'category_id' => $asetTetap
        ]);
    }

    private function mesin($asetTetap, $linkkita): void
    {
        Account::create([
            'code' => '124',
            'name' => 'Mesin',
            'trial_balance_type' => 'debit',
            'category_id' => $asetTetap,
            'company_id' => $linkkita,
        ]);
    }

    private function peralatanInventarisKantor($asetTetap, $linkkita): void
    {
        Account::create([
            'code' => '125',
            'name' => 'Peralatan & Inventaris Kantor',
            'trial_balance_type' => 'debit',
            'category_id' => $asetTetap,
            'company_id' => $linkkita,
        ]);
    }

    private function peralatanInventarisJaringan($asetTetap, $linkkita): void
    {
        Account::create([
            'code' => '126',
            'name' => 'Peralatan & Inventaris Jaringan',
            'trial_balance_type' => 'debit',
            'category_id' => $asetTetap,
            'company_id' => $linkkita,
        ]);
    }

    private function akumulasiPenyusutanAsetTetap($asetTetap, $linkkita): void
    {
        Account::create([
            'code' => '130',
            'name' => 'Akumulasi Penyusutan Aset Tetap',
            'trial_balance_type' => 'credit',
            'category_id' => $asetTetap,
            'company_id' => $linkkita,
        ]);
    }

    private function utangUsaha($utangLancar, $linkkita): void
    {
        Account::create([
            'code' => '211',
            'name' => 'Utang Usaha',
            'trial_balance_type' => 'credit',
            'category_id' => $utangLancar,
            'company_id' => $linkkita,
        ]);
    }

    private function utangDepositAlat($utangLancar, $linkkita): void
    {
        Account::create([
            'code' => '212',
            'name' => 'Utang Deposit Alat',
            'trial_balance_type' => 'credit',
            'category_id' => $utangLancar,
            'company_id' => $linkkita,
        ]);
    }

    private function utangPajak($utangLancar, $linkkita): void
    {
        Account::create([
            'code' => '213',
            'name' => 'Utang Pajak',
            'trial_balance_type' => 'credit',
            'category_id' => $utangLancar,
            'company_id' => $linkkita,
        ]);
    }

    private function pendapatanDiterimaDimuka(mixed $utangLancar, $linkkita): void
    {
        Account::create([
            'code' => '214',
            'name' => 'Pendapatan Diterima Dimuka',
            'trial_balance_type' => 'credit',
            'category_id' => $utangLancar,
            'company_id' => $linkkita,
        ]);
    }

    private function biayaYangMasihHarusDibayar(mixed $utangLancar, $linkkita): void
    {
        $parentAccount = Account::create([
            'code' => '215',
            'name' => 'Biaya yang masih harus dibayar',
            'trial_balance_type' => 'credit',
            'category_id' => $utangLancar,
            'company_id' => $linkkita,
        ]);


        Account::create([
            'name' => 'Utang Gaji',
            'code' => $parentAccount->code . '-' . '01',
            'parent_id' => $parentAccount->id,
        ]);


        Account::create([
            'name' => 'BHP Telekomunikasi',
            'code' => $parentAccount->code . '-' . '02',
            'parent_id' => $parentAccount->id,
        ]);

        Account::create([
            'name' => 'Kontribusi KPU/USO',
            'code' => $parentAccount->code . '-' . '03',
            'parent_id' => $parentAccount->id,
        ]);
    }

    private function utangLancarLainnya(mixed $utangLancar, $linkkita): void
    {
        Account::create([
            'code' => '216',
            'name' => 'Utang lancar lainnya',
            'trial_balance_type' => 'credit',
            'category_id' => $utangLancar,
            'company_id' => $linkkita,
        ]);

    }

    private function utangBank(mixed $utangJangkaPanjang, $linkkita): void
    {
        Account::create([
            'code' => '216',
            'name' => 'Utang lancar lainnya',
            'trial_balance_type' => 'credit',
            'category_id' => $utangJangkaPanjang,
            'company_id' => $linkkita,
        ]);

    }

    private function utangKendaraan(mixed $utangJangkaPanjang, $linkkita): void
    {
        Account::create([
            'code' => '222',
            'name' => 'Utang Kendaraan',
            'trial_balance_type' => 'credit',
            'category_id' => $utangJangkaPanjang,
            'company_id' => $linkkita,
        ]);
    }

    private function utangJangkaPanjangLainnya(mixed $utangJangkaPanjang, $linkkita): void
    {
        Account::create([
            'code' => '223',
            'name' => 'Utang Jangka panjang lainnya',
            'trial_balance_type' => 'credit',
            'category_id' => $utangJangkaPanjang,
            'company_id' => $linkkita,
        ]);
    }

    private function modal($linkkita): void
    {
        Account::create([
            'code' => '300',
            'name' => 'Modal',
            'trial_balance_type' => 'credit',
            'company_id' => $linkkita,
        ]);
    }

    private function labaDitahan($modal, $linkkita): void
    {
        Account::create([
            'code' => '312',
            'name' => 'Laba Ditahan / (Akumulasi Defisit)',
            'trial_balance_type' => 'credit',
            'category_id' => $modal,
            'company_id' => $linkkita,
        ]);
    }

    private function labaRugiBersihPeriodeBerjalan($modal, $linkkita): void
    {
        Account::create([
            'code' => '313',
            'name' => ' Laba / (Rugi) Bersih Periode Berjalan',
            'trial_balance_type' => 'credit',
            'category_id' => $modal,
            'company_id' => $linkkita,
        ]);
    }

    private function dividen($linkkita): void
    {
        Account::create([
            'code' => '320',
            'name' => 'Dividen',
            'trial_balance_type' => 'debit',
            'company_id' => $linkkita,
        ]);
    }

    private function pendapatanJasaLayananInternet($linkkita): void
    {
        $parentAccount = Account::create([
            'code' => '401',
            'name' => 'Pendapatan Jasa Layanan Internet',
            'company_id' => $linkkita,
        ]);


        Account::create([
            'name' => 'Pendapatan Jasa Layanan Internet (Retail)',
            'code' => $parentAccount->code . '-' . '01',
            'parent_id' => $parentAccount->id,
        ]);

        Account::create([
            'name' => 'Pendapatan Jasa Layanan Internet (Corporate)',
            'code' => $parentAccount->code . '-' . '02',
            'parent_id' => $parentAccount->id,
        ]);


    }

    private function pendapatanJasaLayananJaringanTelkom($linkkita): void
    {

        $parentAccount = Account::create([
            'code' => '402',
            'name' => 'Pendapatan Jasa Layanan Jaringan Telekomunikasi',
            'company_id' => $linkkita,
        ]);

        Account::create([
            'name' => 'Pendapatan Jasa Layanan JarTel (Jartaplok)',
            'code' => $parentAccount->code . '-' . '01',
            'parent_id' => $parentAccount->id,
        ]);

        Account::create([
            'name' => ' Pendapatan Jasa Layanan JarTel (Jartup)',
            'code' => $parentAccount->code . '-' . '02',
            'parent_id' => $parentAccount->id,
        ]);
    }

    private function pendapatanLainnya($linkkita): void
    {
        $parentAccount = Account::create([
            'code' => '403',
            'name' => 'Pendapatan Lainnya',
            'company_id' => $linkkita,
        ]);


        Account::create([
            'name' => 'Pendapatan Administrasi Pendaftaran',
            'code' => $parentAccount->code . '-' . '01',
            'parent_id' => $parentAccount->id,
        ]);

        Account::create([
            'name' => 'Pendapatan Dari Penjualan Alat/Perangkat',
            'code' => $parentAccount->code . '-' . '02',
            'parent_id' => $parentAccount->id,
        ]);

        Account::create([
            'name' => 'Pendapatan Bunga Bank',
            'code' => $parentAccount->code . '-' . '03',
            'parent_id' => $parentAccount->id,
        ]);

        Account::create([
            'name' => 'Pendapatan/Penjualan Lainnya',
            'code' => $parentAccount->code . '-' . '04',
            'parent_id' => $parentAccount->id,
        ]);


    }

    private function bebanPokokPendapatan(): void
    {
        $parentAccount = Account::create([
            'code' => '500',
            'name' => 'Beban Pokok Pendapatan',
        ]);


        Account::create([
            'name' => 'Beban Uplink (IP Transit)',
            'code' => $parentAccount->code . '-' . '01',
            'parent_id' => $parentAccount->id,
        ]);

        Account::create([
            'name' => 'Beban Interkoneksi (Metro-Net)',
            'code' => $parentAccount->code . '-' . '02',
            'parent_id' => $parentAccount->id,
        ]);

        Account::create([
            'name' => 'Beban Pokok Lainnya',
            'code' => $parentAccount->code . '-' . '03',
            'parent_id' => $parentAccount->id,
        ]);


    }

    private function bebanPenjualan($linkkita): void
    {
        $parentAccount = Account::create([
            'code' => '501',
            'name' => 'Beban Penjualan',
            'trial_balance_type' => 'debit',
            'company_id' => $linkkita,
        ]);


        Account::create([
            'name' => 'Beban Perlengkapan Jaringan',
            'code' => $parentAccount->code . '-' . '01',
            'parent_id' => $parentAccount->id,
        ]);

        Account::create([
            'name' => 'Beban Jasa Maintenance Jaringan',
            'code' => $parentAccount->code . '-' . '02',
            'parent_id' => $parentAccount->id,
        ]);
        Account::create([
            'name' => ' Beban Promosi & Pemasaran',
            'code' => $parentAccount->code . '-' . '03',
            'parent_id' => $parentAccount->id,
        ]);

        Account::create([
            'name' => 'Beban atas Restitusi Jaringan',
            'code' => $parentAccount->code . '-' . '04',
            'parent_id' => $parentAccount->id,
        ]);

        Account::create([
            'name' => 'Beban BHP Telekomunikasi & Kontribusi USO',
            'code' => $parentAccount->code . '-' . '04',
            'parent_id' => $parentAccount->id,
        ]);
    }

    private function bebanKaryawan($linkkita): void
    {
        $parentAccount = Account::create([
            'code' => '502',
            'name' => 'Beban Karyawan',
            'trial_balance_type' => 'debit',
            'company_id' => $linkkita,
        ]);


        Account::create([
            'name' => 'Beban Gaji Karyawan',
            'code' => $parentAccount->code . '-' . '01',
            'parent_id' => $parentAccount->id,
        ]);

        Account::create([
            'name' => 'Beban BPJS Ketenagakerjaan',
            'code' => $parentAccount->code . '-' . '02',
            'parent_id' => $parentAccount->id,
        ]);

        Account::create([
            'name' => 'Beban BPJS Kesehatan',
            'code' => $parentAccount->code . '-' . '03',
            'parent_id' => $parentAccount->id,
        ]);
    }

    private function bebanUtilitas($linkkita): void
    {
        $parentAccount = Account::create([
            'code' => '503',
            'name' => 'Beban Utilitas',
            'trial_balance_type' => 'debit',
            'company_id' => $linkkita,
        ]);


        Account::create([
            'name' => 'Beban Listrik',
            'code' => $parentAccount->code . '-' . '01',
            'parent_id' => $parentAccount->id,
        ]);

        Account::create([
            'name' => 'Beban Telpon',
            'code' => $parentAccount->code . '-' . '02',
            'parent_id' => $parentAccount->id,
        ]);

        Account::create([
            'name' => 'Beban Air',
            'code' => $parentAccount->code . '-' . '03',
            'parent_id' => $parentAccount->id,
        ]);

        Account::create([
            'name' => 'Beban Gas',
            'code' => $parentAccount->code . '-' . '04',
            'parent_id' => $parentAccount->id,
        ]);

    }

    private function bebanSuppliesKantor($linkkita): void
    {
        $parentAccount = Account::create([
            'code' => '504',
            'name' => 'Beban Supplies Kantor',
            'trial_balance_type' => 'debit',
            'company_id' => $linkkita,
        ]);


        Account::create([
            'name' => 'Beban Perlengkapan Kantor',
            'code' => $parentAccount->code . '-' . '01',
            'parent_id' => $parentAccount->id,
        ]);

        Account::create([
            'name' => 'Beban Perlengkapan Lainnya',
            'code' => $parentAccount->code . '-' . '02',
            'parent_id' => $parentAccount->id,
        ]);

    }

    private function bebangAngkutKirim($linkkita): void
    {
        $parentAccount = Account::create([
            'code' => '505',
            'name' => 'Beban Angkut/Kirim',
            'trial_balance_type' => 'debit',
            'company_id' => $linkkita,
        ]);


        Account::create([
            'name' => ' Beban Angkut Barang',
            'code' => $parentAccount->code . '-' . '01',
            'parent_id' => $parentAccount->id,
        ]);

        Account::create([
            'name' => ' Beban Kirim Dokumen',
            'code' => $parentAccount->code . '-' . '02',
            'parent_id' => $parentAccount->id,
        ]);

        Account::create([
            'name' => ' Beban Angkut/Kirim Lainnya',
            'code' => $parentAccount->code . '-' . '03',
            'parent_id' => $parentAccount->id,
        ]);


    }

    private function bebanPerjalananDinas($linkkita): void
    {
        $parentAccount = Account::create([
            'code' => '506',
            'name' => 'Beban Perjalanan Dinas',
            'trial_balance_type' => 'debit',
            'company_id' => $linkkita,
        ]);

        Account::create([
            'name' => ' Beban Transportasi',
            'code' => $parentAccount->code . '-' . '01',
            'parent_id' => $parentAccount->id,
        ]);

        Account::create([
            'name' => ' Beban Penginapan',
            'code' => $parentAccount->code . '-' . '02',
            'parent_id' => $parentAccount->id,
        ]);

        Account::create([
            'name' => 'Beban Perjalanan Dinas Lainnya',
            'code' => $parentAccount->code . '-' . '03',
            'parent_id' => $parentAccount->id,
        ]);


    }

    private function bebanTransportasiKendaraanAtauMesin($linkkita): void
    {
        $parentAccount = Account::create([
            'code' => '507',
            'name' => 'Beban Transportasi Kendaraan/Mesin',
            'trial_balance_type' => 'debit',
            'company_id' => $linkkita,
        ]);


        Account::create([
            'name' => ' BBM Kendaraan R4 Kantor',
            'code' => $parentAccount->code . '-' . '01',
            'parent_id' => $parentAccount->id,
        ]);
        Account::create([
            'name' => 'BBM Kendaraan R4 Kantor Operasional Lapangan',
            'code' => $parentAccount->code . '-' . '02',
            'parent_id' => $parentAccount->id,
        ]);
        Account::create([
            'name' => 'BBM Kendaraan R3 Kantor Operasional Lapangan',
            'code' => $parentAccount->code . '-' . '03',
            'parent_id' => $parentAccount->id,
        ]);
        Account::create([
            'name' => 'BBM Kendaraan R2 Operasional',
            'code' => $parentAccount->code . '-' . '04',
            'parent_id' => $parentAccount->id,
        ]);
        Account::create([
            'name' => 'Beban Mesin Genset',
            'code' => $parentAccount->code . '-' . '05',
            'parent_id' => $parentAccount->id,
        ]);

    }

    private function bebanPemeliharaanAset($linkkita): void
    {
        $parentAccount = Account::create([
            'code' => '508',
            'name' => 'Beban Pemeliharaan Aset',
            'trial_balance_type' => 'debit',
            'company_id' => $linkkita,
        ]);


        Account::create([
            'name' => ' Beban Pemeliharaan Bangunan',
            'code' => $parentAccount->code . '-' . '01',
            'parent_id' => $parentAccount->id,
        ]);

        Account::create([
            'name' => 'Beban Pemeliharaan Kendaraan',
            'code' => $parentAccount->code . '-' . '02',
            'parent_id' => $parentAccount->id,
        ]);

        Account::create([
            'name' => 'Beban Pemeliharaan Mesin',
            'code' => $parentAccount->code . '-' . '03',
            'parent_id' => $parentAccount->id,
        ]);

        Account::create([
            'name' => 'Beban Pemeliharaan Aset Kantor',
            'code' => $parentAccount->code . '-' . '04',
            'parent_id' => $parentAccount->id,
        ]);

        Account::create([
            'name' => 'Beban Pemeliharaan Jaringan',
            'code' => $parentAccount->code . '-' . '05',
            'parent_id' => $parentAccount->id,
        ]);


    }

    private function bebanSewa($linkkita): void
    {
        Account::create([
            'code' => '510',
            'name' => 'Beban Sewa',
            'trial_balance_type' => 'debit',
            'company_id' => $linkkita,
        ]);

    }

    private function bebanLainLain($linkkita): void
    {

        $parentAccount = Account::create([
            'code' => '511',
            'name' => 'Beban Lain-lain',
            'trial_balance_type' => 'debit',
            'company_id' => $linkkita,
        ]);
        Account::create([
            'name' => 'Retribusi',
            'code' => $parentAccount->code . '-' . '01',
            'parent_id' => $parentAccount->id,
        ]);

        Account::create([
            'name' => 'Beban Pelatihan & Pengembangan SDM',
            'code' => $parentAccount->code . '-' . '02',
            'parent_id' => $parentAccount->id,
        ]);

        Account::create([
            'name' => 'Beban Entertainment',
            'code' => $parentAccount->code . '-' . '03',
            'parent_id' => $parentAccount->id,
        ]);

        Account::create([
            'name' => 'Beban Diluar Usaha Lainnya',
            'code' => $parentAccount->code . '-' . '04',
            'parent_id' => $parentAccount->id,
        ]);
    }

    private function bebanPenyusutan($linkkita): void
    {
        Account::create([
            'code' => '512',
            'name' => 'Beban Penyusutan',
            'trial_balance_type' => 'debit',
            'company_id' => $linkkita,
        ]);
    }

    private function bebanBunga($linkkita): void
    {
        Account::create([
            'code' => '513',
            'name' => 'Beban Bunga',
            'trial_balance_type' => 'debit',
            'company_id' => $linkkita,
        ]);
    }

    private function bebanPajakPenghasilan($linkkita): void
    {
        Account::create([
            'code' => '514',
            'name' => 'Beban Pajak Penghasilan',
            'trial_balance_type' => 'debit',
            'company_id' => $linkkita,
        ]);
    }


}
