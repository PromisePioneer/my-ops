<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\SubAccount;
use Illuminate\Database\Seeder;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Account::create([
            'branch_id' => 1,
            'code' => '111',
            'name' => 'Kas dan Setara Kas',

        ]);

        Account::create([
            'branch_id' => 1,
            'code' => '112',
            'name' => 'Persediaan Barang Jaringan',
        ]);

        Account::create([
            'branch_id' => 1,
            'code' => '113',
            'name' => 'Piutang Usaha',
        ]);

        Account::create([
            'branch_id' => 1,
            'code' => '114',
            'name' => 'Biaya dibayar dimuka',
        ]);


        Account::create([
            'branch_id' => 1,
            'code' => '115',
            'name' => 'Pajak dibayar dimuka',
        ]);

        Account::create([
            'branch_id' => 1,
            'code' => '121',
            'name' => 'Tanah',
        ]);

        Account::create([
            'branch_id' => 1,
            'code' => '122',
            'name' => 'Bangunan',
        ]);

        Account::create([
            'branch_id' => 1,
            'code' => '123',
            'name' => 'Kendaraan',
        ]);

        Account::create([
            'branch_id' => 1,
            'code' => '124',
            'name' => 'Mesin',
        ]);

        Account::create([
            'branch_id' => 1,
            'code' => '125',
            'name' => 'Peralatan & Inventaris Kantor',
        ]);

        Account::create([
            'branch_id' => 1,
            'code' => '126',
            'name' => 'Peralatan & Inventaris Jaringan',
        ]);

        Account::create([
            'branch_id' => 1,
            'code' => '130',
            'name' => 'Akumulasi Penyusutan Aset Tetap',
        ]);


        Account::create([
            'branch_id' => 1,
            'code' => '211',
            'name' => 'Utang Usaha'
        ]);


        Account::create([
            'branch_id' => 1,
            'code' => '212',
            'name' => 'Utang Deposit Alat'
        ]);

        Account::create([
            'branch_id' => 1,
            'code' => '213',
            'name' => 'Utang Pajak'
        ]);

        Account::create([
            'branch_id' => 1,
            'code' => '214',
            'name' => 'Pendapatan Diterima Dimuka'
        ]);

        Account::create([
            'branch_id' => 1,
            'code' => '215',
            'name' => 'Biaya yang masih harus dibayar'
        ]);

        Account::create([
            'branch_id' => 1,
            'code' => '216',
            'name' => 'Utang lancar lainnya'
        ]);

        Account::create([
            'branch_id' => 1,
            'code' => '221',
            'name' => 'Utang Bank'
        ]);

        Account::create([
            'branch_id' => 1,
            'code' => '222',
            'name' => 'Utang Kendaraan'
        ]);

        Account::create([
            'branch_id' => 1,
            'code' => '223',
            'name' => 'Utang Jangka panjang lainnya'
        ]);

        Account::create([
            'branch_id' => 1,
            'code' => '300',
            'name' => 'Modal'
        ]);

        Account::create([
            'branch_id' => 1,
            'code' => '310',
            'name' => 'Modal Saham'
        ]);

        Account::create([
            'branch_id' => 1,
            'code' => '311',
            'name' => 'Modal Lainnya'
        ]);

        Account::create([
            'branch_id' => 1,
            'code' => '312',
            'name' => 'Saldo laba ditahan'
        ]);

        Account::create([
            'branch_id' => 1,
            'code' => '313',
            'name' => 'Laba bersih periode berjalan'
        ]);

        Account::create([
            'branch_id' => 1,
            'code' => '320',
            'name' => 'Dividen'
        ]);

        Account::create([
            'branch_id' => 1,
            'code' => '401',
            'name' => 'Pendapatan Jasa Layanan Internet'
        ]);

        Account::create([
            'branch_id' => 1,
            'code' => '402',
            'name' => 'Pendapatan Jasa Layanan Jaringan Telekomunikasi'
        ]);

        Account::create([
            'branch_id' => 1,
            'code' => '403',
            'name' => 'Pendapatan Lainnya'
        ]);

        Account::create([
            'branch_id' => 1,
            'code' => '500',
            'name' => 'Beban Pokok Pendapatan'
        ]);

        Account::create([
            'branch_id' => 1,
            'code' => '501',
            'name' => 'Beban Penjualan'
        ]);

        Account::create([
            'branch_id' => 1,
            'code' => '502',
            'name' => 'Beban Karyawan'
        ]);


        Account::create([
            'branch_id' => 1,
            'code' => '503',
            'name' => 'Beban Utilitas'
        ]);


        Account::create([
            'branch_id' => 1,
            'code' => '504',
            'name' => 'Beban Supplies Kantor'
        ]);

        Account::create([
            'branch_id' => 1,
            'code' => '505',
            'name' => 'Beban Angkut/Kirim'
        ]);

        Account::create([
            'branch_id' => 1,
            'code' => '506',
            'name' => 'Beban Perjalanan Dinas'
        ]);

        Account::create([
            'branch_id' => 1,
            'code' => '507',
            'name' => 'Beban Transportasi Kendaraan/Mesin'
        ]);

        Account::create([
            'branch_id' => 1,
            'code' => '508',
            'name' => 'Beban Pemeliharaan Aset'
        ]);

        Account::create([
            'branch_id' => 1,
            'code' => '510',
            'name' => 'Beban Sewa'
        ]);

        Account::create([
            'branch_id' => 1,
            'code' => '511',
            'name' => 'Beban Lain-lain'
        ]);

        Account::create([
            'branch_id' => 1,
            'code' => '512',
            'name' => 'Beban Penyusutan'
        ]);

        Account::create([
            'branch_id' => 1,
            'code' => '513',
            'name' => 'Beban Bunga'
        ]);

        Account::create([
            'branch_id' => 1,
            'code' => '514',
            'name' => 'Beban Pajak Penghasilan'
        ]);

    }
}
