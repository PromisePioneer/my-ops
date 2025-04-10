<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\AccountCategory;
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

        $asetLancar = AccountCategory::where('name', 'Aset Lancar')->first()->id;
        $asetTetap = AccountCategory::where('name', 'Aset Tetap')->first()->id;
        $utangLancar = AccountCategory::where('name', 'Utang Lancar')->first()->id;
        $utangJangkaPanjang = AccountCategory::where('name', 'Utang Jangka Panjang')->first()->id;
        $modal = AccountCategory::where('name', 'Modal')->first()->id;


        $id1 = Account::create([
            'code' => '111',
            'name' => 'Kas dan Setara Kas',
            'trial_balance_type' => 'debit',
            'category_id' => $asetLancar,
        ]);

        $id2 = Account::create([
            'code' => '112',
            'name' => 'Persediaan Barang Jaringan',
            'trial_balance_type' => 'debit',
            'category_id' => $asetLancar,
        ]);

        $id3 = Account::create([
            'code' => '113',
            'name' => 'Piutang Usaha',
            'trial_balance_type' => 'debit',
            'category_id' => $asetLancar,
        ]);

        $id4 = Account::create([
            'code' => '114',
            'name' => 'Biaya dibayar dimuka',
            'trial_balance_type' => 'debit',
            'category_id' => $asetLancar,
        ]);


        $id5 = Account::create([
            'code' => '115',
            'name' => 'Pajak dibayar dimuka',
            'trial_balance_type' => 'debit',
            'category_id' => 1,
        ]);

        $id6 = Account::create([
            'code' => '121',
            'name' => 'Tanah',
            'trial_balance_type' => 'debit',
            'category_id' => $asetTetap
        ]);

        $id7 = Account::create([
            'code' => '122',
            'name' => 'Bangunan',
            'trial_balance_type' => 'debit',
            'category_id' => $asetTetap
        ]);

        $id8 = Account::create([
            'code' => '123',
            'name' => 'Kendaraan',
            'trial_balance_type' => 'debit',
            'category_id' => $asetTetap
        ]);

        $id9 = Account::create([
            'code' => '124',
            'name' => 'Mesin',
            'trial_balance_type' => 'debit',
            'category_id' => $asetTetap
        ]);

        $id10 = Account::create([
            'code' => '125',
            'name' => 'Peralatan & Inventaris Kantor',
            'trial_balance_type' => 'debit',
            'category_id' => $asetTetap
        ]);

        $id11 = Account::create([
            'code' => '126',
            'name' => 'Peralatan & Inventaris Jaringan',
            'trial_balance_type' => 'debit',
            'category_id' => $asetTetap
        ]);

        $id12 = Account::create([
            'code' => '130',
            'name' => 'Akumulasi Penyusutan Aset Tetap',
            'trial_balance_type' => 'credit',
            'category_id' => $asetTetap
        ]);

        $id13 = Account::create([
            'code' => '211',
            'name' => 'Utang Usaha',
            'trial_balance_type' => 'credit',
            'category_id' => $utangLancar,
        ]);

        $id14 = Account::create([
            'code' => '212',
            'name' => 'Utang Deposit Alat',
            'trial_balance_type' => 'credit',
            'category_id' => $utangLancar,
        ]);

        $id15 = Account::create([
            'code' => '213',
            'name' => 'Utang Pajak',
            'trial_balance_type' => 'credit',
            'category_id' => $utangLancar,
        ]);

        $id16 = Account::create([
            'code' => '214',
            'name' => 'Pendapatan Diterima Dimuka',
            'trial_balance_type' => 'credit',
            'category_id' => $utangLancar,
        ]);

        $id17 = Account::create([
            'code' => '215',
            'name' => 'Biaya yang masih harus dibayar',
            'trial_balance_type' => 'credit',
            'category_id' => $utangLancar,
        ]);

        $id18 = Account::create([
            'code' => '216',
            'name' => 'Utang lancar lainnya',
            'trial_balance_type' => 'credit',
            'category_id' => $utangLancar,
        ]);

        $id19 = Account::create([
            'code' => '221',
            'name' => 'Utang Bank',
            'trial_balance_type' => 'credit',
            'category_id' => $utangJangkaPanjang,
        ]);

        $id20 = Account::create([
            'code' => '222',
            'name' => 'Utang Kendaraan',
            'trial_balance_type' => 'credit',
            'category_id' => $utangJangkaPanjang,
        ]);

        $id21 = Account::create([
            'code' => '223',
            'name' => 'Utang Jangka panjang lainnya',
            'trial_balance_type' => 'credit',
            'category_id' => $utangJangkaPanjang,
        ]);

        $id22 = Account::create([
            'code' => '300',
            'name' => 'Modal',
            'trial_balance_type' => 'credit'
        ]);

        $id25 = Account::create([
            'code' => '312',
            'name' => 'Saldo laba ditahan',
            'trial_balance_type' => 'credit',
            'category_id' => $modal,
        ]);

        $id26 = Account::create([
            'code' => '313',
            'name' => 'Laba bersih periode berjalan',
            'trial_balance_type' => 'credit',
            'category_id' => $modal,
        ]);

        $id27 = Account::create([
            'code' => '320',
            'name' => 'Dividen',
            'trial_balance_type' => 'debit',
        ]);

        $id28 = Account::create([
            'code' => '401',
            'name' => 'Pendapatan Jasa Layanan Internet',
        ]);

        $id29 = Account::create([
            'code' => '402',
            'name' => 'Pendapatan Jasa Layanan Jaringan Telekomunikasi',
        ]);

        $id30 = Account::create([
            'code' => '403',
            'name' => 'Pendapatan Lainnya',
        ]);

        $id31 = Account::create([
            'code' => '500',
            'name' => 'Beban Pokok Pendapatan',
        ]);

        $id32 = Account::create([
            'code' => '501',
            'name' => 'Beban Penjualan',
            'trial_balance_type' => 'debit',

        ]);

        $id33 = Account::create([
            'code' => '502',
            'name' => 'Beban Karyawan',
            'trial_balance_type' => 'debit',

        ]);

        $id34 = Account::create([
            'code' => '503',
            'name' => 'Beban Utilitas',
            'trial_balance_type' => 'debit',

        ]);

        $id35 = Account::create([
            'code' => '504',
            'name' => 'Beban Supplies Kantor',
            'trial_balance_type' => 'debit',

        ]);

        $id36 = Account::create([
            'code' => '505',
            'name' => 'Beban Angkut/Kirim',
            'trial_balance_type' => 'debit',

        ]);

        $id37 = Account::create([
            'code' => '506',
            'name' => 'Beban Perjalanan Dinas',
            'trial_balance_type' => 'debit',
        ]);

        $id38 = Account::create([
            'code' => '507',
            'name' => 'Beban Transportasi Kendaraan/Mesin',
            'trial_balance_type' => 'debit',

        ]);

        $id39 = Account::create([
            'code' => '508',
            'name' => 'Beban Pemeliharaan Aset',
            'trial_balance_type' => 'debit',

        ]);

        $id40 = Account::create([
            'code' => '510',
            'name' => 'Beban Sewa',
            'trial_balance_type' => 'debit',
        ]);

        $id41 = Account::create([
            'code' => '511',
            'name' => 'Beban Lain-lain',
            'trial_balance_type' => 'debit',
        ]);

        $id42 = Account::create([
            'code' => '512',
            'name' => 'Beban Penyusutan',
            'trial_balance_type' => 'debit',
        ]);

        $id43 = Account::create([
            'code' => '513',
            'name' => 'Beban Bunga',
            'trial_balance_type' => 'debit',
        ]);

        $id44 = Account::create([
            'code' => '514',
            'name' => 'Beban Pajak Penghasilan',
            'trial_balance_type' => 'debit',
        ]);


        //1
        Account::create([
            'name' => 'Kas Tunai',
            'code' => $id1->code . '-' . '01',
            'parent_id' => 1,
        ]);

        Account::create([
            'name' => 'Rekening Cabang',
            'code' => $id1->code . '-' . '02',
            'parent_id' => 1,
        ]);

        Account::create([
            'name' => 'Rekening Pendapatan',
            'code' => $id1->code . '-' . '03',
            'parent_id' => 1,
        ]);

        Account::create([
            'name' => 'Rekening Mayatama Pusat',
            'code' => $id1->code . '-' . '04',
            'parent_id' => 1,
        ]);

        //2
        Account::create([
            'name' => 'Persediaan Perlengkapan Jaringan',
            'code' => $id2->code . '-' . '01',
            'parent_id' => 2,
        ]);

        Account::create([
            'name' => 'Persediaan Lainnya',
            'code' => $id2->code . '-' . '02',
            'parent_id' => 2,
        ]);

        //3
        Account::create([
            'name' => 'Piutang Pelanggan',
            'code' => $id3->code . '-' . '01',
            'parent_id' => 3,
        ]);

        Account::create([
            'name' => 'Piutang Lainnya',
            'code' => $id3->code . '-' . '02',
            'parent_id' => 3,
        ]);

        //4
        Account::create([
            'name' => 'Sewa dibayar dimuka',
            'code' => $id4->code . '-' . '01',
            'parent_id' => 4,
        ]);

        Account::create([
            'name' => 'Sewa dibayar dimuka lainnya',
            'code' => $id4->code . '-' . '02',
            'parent_id' => 4,
        ]);

        //5
        Account::create([
            'name' => 'PPn Masukan',
            'code' => $id5->code . '-' . '01',
            'parent_id' => 5,
        ]);

        Account::create([
            'name' => 'Kredit PPh 23',
            'code' => $id5->code . '-' . '02',
            'parent_id' => 5,
        ]);

        Account::create([
            'name' => 'Kredit Angsuran PPh 25',
            'code' => $id5->code . '-' . '03',
            'parent_id' => 5,
        ]);

        //6
        Account::create([
            'name' => 'Utang PPn',
            'code' => $id15->code . '-' . '01',
            'parent_id' => 15,
        ]);

        Account::create([
            'name' => 'Utang PPh Pasal 21',
            'code' => $id15->code . '-' . '02',
            'parent_id' => 15,
        ]);

        Account::create([
            'name' => 'Utang PPh Pasal 23',
            'code' => $id15->code . '-' . '03',
            'parent_id' => 15,
        ]);

        Account::create([
            'name' => 'Utang PPh Pasal 29',
            'code' => $id15->code . '-' . '04',
            'parent_id' => 15,
        ]);

        Account::create([
            'name' => 'Utang PPh Pasal 4 ayat (2)',
            'code' => $id15->code . '-' . '05',
            'parent_id' => 15,
        ]);

        Account::create([
            'name' => 'Utang Gaji',
            'code' => $id17->code . '-' . '01',
            'parent_id' => 17,
        ]);

        Account::create([
            'name' => 'BHP Telekomunikasi',
            'code' => $id17->code . '-' . '02',
            'parent_id' => 17,
        ]);

        Account::create([
            'name' => 'Kontribusi KPU/USO',
            'code' => $id17->code . '-' . '03',
            'parent_id' => 17,
        ]);

        Account::create([
            'name' => 'Pendapatan Layanan Internet Broadband',
            'code' => $id28->code . '-' . '01',
            'parent_id' => 28,
        ]);

        Account::create([
            'name' => 'Pendapatan Layanan Internet Dedicated',
            'code' => $id28->code . '-' . '02',
            'parent_id' => 28,
        ]);

        Account::create([
            'name' => 'Pendapatan Layanan Jartaplok',
            'code' => $id29->code . '-' . '01',
            'parent_id' => 29,
        ]);

        Account::create([
            'name' => 'Pendapatan Layanan Jartup',
            'code' => $id29->code . '-' . '02',
            'parent_id' => 29,
        ]);

        Account::create([
            'name' => 'Pendapatan Administrasi Pendaftaran',
            'code' => $id30->code . '-' . '01',
            'parent_id' => 30,
        ]);

        Account::create([
            'name' => 'Penjualan Alat dan Perangkat',
            'code' => $id30->code . '-' . '02',
            'parent_id' => 30,
        ]);

        Account::create([
            'name' => 'Pendapatan Bunga Bank',
            'code' => $id30->code . '-' . '03',
            'parent_id' => 30,
        ]);

        Account::create([
            'name' => 'Pendapatan/Penjualan Jasa Lainnya',
            'code' => $id30->code . '-' . '04',
            'parent_id' => 30,
        ]);

        Account::create([
            'name' => 'Beban Uplink',
            'code' => $id31->code . '-' . '01',
            'parent_id' => 31,
        ]);

        Account::create([
            'name' => 'Beban Interkoneksi',
            'code' => $id31->code . '-' . '02',
            'parent_id' => 31,
        ]);

        Account::create([
            'name' => 'Beban Pokok Lainnya',
            'code' => $id31->code . '-' . '03',
            'parent_id' => 31,
        ]);

        Account::create([
            'name' => 'Perlengkapan Jaringan',
            'code' => $id32->code . '-' . '01',
            'parent_id' => 32,
        ]);

        Account::create([
            'name' => 'Jasa Vendor',
            'code' => $id32->code . '-' . '02',
            'parent_id' => 32,
        ]);

        Account::create([
            'name' => 'BHP Tel & KPU/USO',
            'code' => $id32->code . '-' . '03',
            'parent_id' => 32,
        ]);

        Account::create([
            'name' => 'Beban Gaji Karyawan',
            'code' => $id33->code . '-' . '01',
            'parent_id' => 33,
        ]);

        Account::create([
            'name' => 'Beban BPJS Ketenagakerjaan',
            'code' => $id33->code . '-' . '02',
            'parent_id' => 33,
        ]);

        Account::create([
            'name' => 'Beban BPJS Kesehatan',
            'code' => $id33->code . '-' . '03',
            'parent_id' => 33,
        ]);

        Account::create([
            'name' => 'Beban Listrik',
            'code' => $id34->code . '-' . '01',
            'parent_id' => 34,
        ]);

        Account::create([
            'name' => 'Beban Telpon',
            'code' => $id34->code . '-' . '02',
            'parent_id' => 34,
        ]);

        Account::create([
            'name' => 'Beban Air',
            'code' => $id34->code . '-' . '03',
            'parent_id' => 34,
        ]);

        Account::create([
            'name' => 'Beban Gas',
            'code' => $id34->code . '-' . '04',
            'parent_id' => 34,
        ]);

        Account::create([
            'name' => 'Beban Perlengkapan Kantor',
            'code' => $id35->code . '-' . '01',
            'parent_id' => 35,
        ]);

        Account::create([
            'name' => 'Beban Perlengkapan Lainnya',
            'code' => $id35->code . '-' . '02',
            'parent_id' => 35,
        ]);

        Account::create([
            'name' => ' Beban Angkut Barang',
            'code' => $id36->code . '-' . '01',
            'parent_id' => 36,
        ]);

        Account::create([
            'name' => ' Beban Kirim Dokumen',
            'code' => $id36->code . '-' . '02',
            'parent_id' => 36,
        ]);

        Account::create([
            'name' => ' Beban Angkut/Kirim Lainnya',
            'code' => $id36->code . '-' . '03',
            'parent_id' => 36,
        ]);

        Account::create([
            'name' => ' Beban Transportasi',
            'code' => $id37->code . '-' . '01',
            'parent_id' => 37,
        ]);

        Account::create([
            'name' => ' Beban Penginapan',
            'code' => $id37->code . '-' . '02',
            'parent_id' => 37,
        ]);

        Account::create([
            'name' => 'Beban Perjalanan Dinas Lainnya',
            'code' => $id37->code . '-' . '03',
            'parent_id' => 37,
        ]);

        Account::create([
            'name' => ' BBM Kendaraan R4 Kantor',
            'code' => $id38->code . '-' . '01',
            'parent_id' => 38,
        ]);
        Account::create([
            'name' => 'BBM Kendaraan R4 Kantor Operasional Lapangan',
            'code' => $id38->code . '-' . '02',
            'parent_id' => 38,
        ]);
        Account::create([
            'name' => 'BBM Kendaraan R3 Kantor Operasional Lapangan',
            'code' => $id38->code . '-' . '03',
            'parent_id' => 38,
        ]);
        Account::create([
            'name' => 'BBM Kendaraan R2 Operasional',
            'code' => $id38->code . '-' . '04',
            'parent_id' => 38,
        ]);
        Account::create([
            'name' => 'Beban Mesin Genset',
            'code' => $id38->code . '-' . '05',
            'parent_id' => 38,
        ]);

        Account::create([
            'name' => ' Beban Pemeliharaan Bangunan',
            'code' => $id39->code . '-' . '01',
            'parent_id' => 39,
        ]);

        Account::create([
            'name' => 'Beban Pemeliharaan Kendaraan',
            'code' => $id39->code . '-' . '02',
            'parent_id' => 39,
        ]);

        Account::create([
            'name' => 'Beban Pemeliharaan Mesin',
            'code' => $id39->code . '-' . '03',
            'parent_id' => 39,
        ]);

        Account::create([
            'name' => 'Beban Pemeliharaan Aset Kantor',
            'code' => $id39->code . '-' . '04',
            'parent_id' => 39,
        ]);

        Account::create([
            'name' => 'Beban Pemeliharaan Jaringan',
            'code' => $id39->code . '-' . '05',
            'parent_id' => 39,
        ]);

        Account::create([
            'name' => 'Retribusi',
            'code' => $id41->code . '-' . '01',
            'parent_id' => 41,
        ]);

        Account::create([
            'name' => 'Beban Pelatihan & Pengembangan SDM',
            'code' => $id41->code . '-' . '02',
            'parent_id' => 41,
        ]);

        Account::create([
            'name' => 'Beban Entertainment',
            'code' => $id41->code . '-' . '03',
            'parent_id' => 41,
        ]);

        Account::create([
            'name' => 'Beban Diluar Usaha Lainnya',
            'code' => $id41->code . '-' . '04',
            'parent_id' => 41,
        ]);
    }
}
