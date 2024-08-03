<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\SubAccount;
use Illuminate\Database\Seeder;

class SubAccountSeeder extends Seeder
{
    public function run()
    {


        $id1 = Account::where('id', 1)->first();
        $id2 = Account::where('id', 2)->first();
        $id3 = Account::where('id', 3)->first();
        $id4 = Account::where('id', 4)->first();
        $id5 = Account::where('id', 5)->first();
        $id6 = Account::where('id', 6)->first();
        $id7 = Account::where('id', 7)->first();
        $id8 = Account::where('id', 8)->first();
        $id9 = Account::where('id', 9)->first();
        $id10 = Account::where('id', 10)->first();
        $id11 = Account::where('id', 11)->first();
        $id12 = Account::where('id', 12)->first();
        $id13 = Account::where('id', 13)->first();
        $id14 = Account::where('id', 14)->first();
        $id15 = Account::where('id', 15)->first();
        $id16 = Account::where('id', 16)->first();
        $id17 = Account::where('id', 17)->first();
        $id18 = Account::where('id', 18)->first();
        $id19 = Account::where('id', 19)->first();
        $id20 = Account::where('id', 20)->first();
        $id21 = Account::where('id', 21)->first();
        $id22 = Account::where('id', 22)->first();
        $id23 = Account::where('id', 23)->first();
        $id24 = Account::where('id', 24)->first();
        $id25 = Account::where('id', 25)->first();
        $id26 = Account::where('id', 26)->first();
        $id27 = Account::where('id', 27)->first();
        $id28 = Account::where('id', 28)->first();
        $id29 = Account::where('id', 29)->first();
        $id30 = Account::where('id', 30)->first();
        $id31 = Account::where('id', 31)->first();
        $id32 = Account::where('id', 32)->first();
        $id33 = Account::where('id', 33)->first();
        $id34 = Account::where('id', 34)->first();
        $id35 = Account::where('id', 35)->first();
        $id36 = Account::where('id', 36)->first();
        $id37 = Account::where('id', 37)->first();
        $id38 = Account::where('id', 38)->first();
        $id39 = Account::where('id', 39)->first();
        $id40 = Account::where('id', 40)->first();
        $id41 = Account::where('id', 41)->first();
        $id42 = Account::where('id', 42)->first();
        $id43 = Account::where('id', 43)->first();
        $id44 = Account::where('id', 44)->first();
        $id45 = Account::where('id', 45)->first();


        //1
        SubAccount::create([
            'name' => 'Kas Tunai',
            'code' => $id1->code . '-' . "01",
            'account_id' => 1
        ]);

        SubAccount::create([
            'name' => 'Rekening Cabang',
            'code' => $id1->code . '-' . "02",
            'account_id' => 1
        ]);

        SubAccount::create([
            'name' => 'Rekening Pendapatan',
            'code' => $id1->code . '-' . "03",
            'account_id' => 1
        ]);


        SubAccount::create([
            'name' => 'Rekening Mayatama Pusat',
            'code' => $id1->code . '-' . "04",
            'account_id' => 1
        ]);


        //2
        SubAccount::create([
            'name' => 'Persediaan Perlengkapan Jaringan',
            'code' => $id2->code . '-' . "01",
            'account_id' => 2
        ]);

        SubAccount::create([
            'name' => 'Persediaan Lainnya',
            'code' => $id2->code . '-' . "02",
            'account_id' => 2
        ]);


        //3
        SubAccount::create([
            'name' => 'Piutang Pelanggan',
            'code' => $id3->code . '-' . "01",
            'account_id' => 3
        ]);

        SubAccount::create([
            'name' => 'Piutang Lainnya',
            'code' => $id3->code . '-' . "02",
            'account_id' => 3
        ]);

        //4
        SubAccount::create([
            'name' => 'Sewa dibayar dimuka',
            'code' => $id4->code . '-' . "01",
            'account_id' => 4
        ]);

        SubAccount::create([
            'name' => 'Sewa dibayar dimuka lainnya',
            'code' => $id4->code . '-' . "02",
            'account_id' => 4
        ]);

        //5
        SubAccount::create([
            'name' => 'PPn Masukan',
            'code' => $id5->code . '-' . "01",
            'account_id' => 5
        ]);

        SubAccount::create([
            'name' => 'Kredit PPh 23',
            'code' => $id5->code . '-' . "02",
            'account_id' => 5
        ]);

        SubAccount::create([
            'name' => 'Kredit Angsuran PPh 25',
            'code' => $id5->code . '-' . "03",
            'account_id' => 5
        ]);


        //6
        SubAccount::create([
            'name' => 'Utang PPn',
            'code' => $id15->code . '-' . "01",
            'account_id' => 15
        ]);

        SubAccount::create([
            'name' => 'Utang PPh Pasal 21',
            'code' => $id15->code . '-' . "02",
            'account_id' => 15
        ]);

        SubAccount::create([
            'name' => 'Utang PPh Pasal 23',
            'code' => $id15->code . '-' . "03",
            'account_id' => 15
        ]);

        SubAccount::create([
            'name' => 'Utang PPh Pasal 29',
            'code' => $id15->code . '-' . "04",
            'account_id' => 15
        ]);

        SubAccount::create([
            'name' => 'Utang PPh Pasal 4 ayat (2)',
            'code' => $id15->code . '-' . "05",
            'account_id' => 15
        ]);


        SubAccount::create([
            'name' => 'Utang Gaji',
            'code' => $id17->code . '-' . "01",
            'account_id' => 17
        ]);

        SubAccount::create([
            'name' => 'BHP Telekomunikasi',
            'code' => $id17->code . '-' . "02",
            'account_id' => 17
        ]);


        SubAccount::create([
            'name' => 'Kontribusi KPU/USO',
            'code' => $id17->code . '-' . "03",
            'account_id' => 17
        ]);


        SubAccount::create([
            'name' => 'Pendapatan Layanan Internet Broadband',
            'code' => $id28->code . '-' . "01",
            'account_id' => 28
        ]);

        SubAccount::create([
            'name' => 'Pendapatan Layanan Internet Dedicated',
            'code' => $id28->code . '-' . "02",
            'account_id' => 28
        ]);

        SubAccount::create([
            'name' => 'Pendapatan Layanan Jartaplok',
            'code' => $id29->code . '-' . "01",
            'account_id' => 29
        ]);

        SubAccount::create([
            'name' => 'Pendapatan Layanan Jartup',
            'code' => $id29->code . '-' . "02",
            'account_id' => 29
        ]);


        SubAccount::create([
            'name' => 'Pendapatan Administrasi Pendaftaran',
            'code' => $id30->code . '-' . "01",
            'account_id' => 30
        ]);

        SubAccount::create([
            'name' => 'Penjualan Alat dan Perangkat',
            'code' => $id30->code . '-' . "02",
            'account_id' => 30
        ]);

        SubAccount::create([
            'name' => 'Pendapatan Bunga Bank',
            'code' => $id30->code . '-' . "03",
            'account_id' => 30
        ]);

        SubAccount::create([
            'name' => 'Pendapatan/Penjualan Jasa Lainnya',
            'code' => $id30->code . '-' . "04",
            'account_id' => 30
        ]);

        SubAccount::create([
            'name' => 'Beban Uplink',
            'code' => $id31->code . '-' . "01",
            'account_id' => 31
        ]);


        SubAccount::create([
            'name' => 'Beban Interkoneksi',
            'code' => $id31->code . '-' . "02",
            'account_id' => 31
        ]);

        SubAccount::create([
            'name' => 'Beban Pokok Lainnya',
            'code' => $id31->code . '-' . "03",
            'account_id' => 31
        ]);


        SubAccount::create([
            'name' => 'Perlengkapan Jaringan',
            'code' => $id32->code . '-' . "01",
            'account_id' => 32
        ]);

        SubAccount::create([
            'name' => 'Jasa Vendor',
            'code' => $id32->code . '-' . "02",
            'account_id' => 32
        ]);


        SubAccount::create([
            'name' => 'BHP Tel & KPU/USO',
            'code' => $id32->code . '-' . "03",
            'account_id' => 32
        ]);

        SubAccount::create([
            'name' => 'Beban Gaji Karyawan',
            'code' => $id33->code . '-' . "01",
            'account_id' => 33
        ]);

        SubAccount::create([
            'name' => 'Beban BPJS Ketenagakerjaan',
            'code' => $id33->code . '-' . "02",
            'account_id' => 33
        ]);

        SubAccount::create([
            'name' => 'Beban BPJS Kesehatan',
            'code' => $id33->code . '-' . "03",
            'account_id' => 33
        ]);


        SubAccount::create([
            'name' => 'Beban Listrik',
            'code' => $id34->code . '-' . "01",
            'account_id' => 34
        ]);


        SubAccount::create([
            'name' => 'Beban Telpon',
            'code' => $id34->code . '-' . "02",
            'account_id' => 34
        ]);


        SubAccount::create([
            'name' => 'Beban Air',
            'code' => $id34->code . '-' . "03",
            'account_id' => 34
        ]);


        SubAccount::create([
            'name' => 'Beban Gas',
            'code' => $id34->code . '-' . "04",
            'account_id' => 34
        ]);


        SubAccount::create([
            'name' => 'Beban Perlengkapan Kantor',
            'code' => $id35->code . '-' . "01",
            'account_id' => 35
        ]);

        SubAccount::create([
            'name' => 'Beban Perlengkapan Lainnya',
            'code' => $id35->code . '-' . "02",
            'account_id' => 35
        ]);


        SubAccount::create([
            'name' => ' Beban Angkut Barang',
            'code' => $id36->code . '-' . "01",
            'account_id' => 36
        ]);

        SubAccount::create([
            'name' => ' Beban Kirim Dokumen',
            'code' => $id36->code . '-' . "02",
            'account_id' => 36
        ]);

        SubAccount::create([
            'name' => ' Beban Angkut/Kirim Lainnya',
            'code' => $id36->code . '-' . "03",
            'account_id' => 36
        ]);


        SubAccount::create([
            'name' => ' Beban Transportasi',
            'code' => $id37->code . '-' . "01",
            'account_id' => 37
        ]);

        SubAccount::create([
            'name' => ' Beban Penginapan',
            'code' => $id37->code . '-' . "02",
            'account_id' => 37
        ]);

        SubAccount::create([
            'name' => 'Beban Perjalanan Dinas Lainnya',
            'code' => $id37->code . '-' . "03",
            'account_id' => 37
        ]);


        SubAccount::create([
            'name' => ' BBM Kendaraan R4 Kantor',
            'code' => $id38->code . '-' . "01",
            'account_id' => 38
        ]);
        SubAccount::create([
            'name' => 'BBM Kendaraan R4 Kantor Operasional Lapangan',
            'code' => $id38->code . '-' . "02",
            'account_id' => 38
        ]);
        SubAccount::create([
            'name' => 'BBM Kendaraan R3 Kantor Operasional Lapangan',
            'code' => $id38->code . '-' . "03",
            'account_id' => 38
        ]);
        SubAccount::create([
            'name' => 'BBM Kendaraan R2 Operasional',
            'code' => $id38->code . '-' . "04",
            'account_id' => 38
        ]);
        SubAccount::create([
            'name' => 'Beban Mesin Genset',
            'code' => $id38->code . '-' . "05",
            'account_id' => 38
        ]);


        SubAccount::create([
            'name' => ' Beban Pemeliharaan Bangunan',
            'code' => $id39->code . '-' . "01",
            'account_id' => 39
        ]);

        SubAccount::create([
            'name' => 'Beban Pemeliharaan Kendaraan',
            'code' => $id39->code . '-' . "02",
            'account_id' => 39
        ]);

        SubAccount::create([
            'name' => 'Beban Pemeliharaan Mesin',
            'code' => $id39->code . '-' . "03",
            'account_id' => 39
        ]);

        SubAccount::create([
            'name' => 'Beban Pemeliharaan Aset Kantor',
            'code' => $id39->code . '-' . "04",
            'account_id' => 39
        ]);

        SubAccount::create([
            'name' => 'Beban Pemeliharaan Jaringan',
            'code' => $id39->code . '-' . "05",
            'account_id' => 39
        ]);


        SubAccount::create([
            'name' => 'Retribusi',
            'code' => $id41->code . '-' . "01",
            'account_id' => 41
        ]);

        SubAccount::create([
            'name' => 'Beban Pelatihan & Pengembangan SDM',
            'code' => $id41->code . '-' . "02",
            'account_id' => 41
        ]);

        SubAccount::create([
            'name' => 'Beban Entertainment',
            'code' => $id41->code . '-' . "03",
            'account_id' => 41
        ]);


        SubAccount::create([
            'name' => 'Beban Diluar Usaha Lainnya',
            'code' => $id41->code . '-' . "04",
            'account_id' => 41
        ]);


    }
}
