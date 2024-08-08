<?php

namespace Database\Seeders;

use App\Models\Account;
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
            'code' => '111',
            'name' => 'Kas dan Setara Kas',

        ]);

        Account::create([
            'code' => '112',
            'name' => 'Persediaan Barang Jaringan',
        ]);

        Account::create([
            'code' => '113',
            'name' => 'Piutang Usaha',
        ]);

        Account::create([
            'code' => '114',
            'name' => 'Biaya dibayar dimuka',
        ]);

        Account::create([
            'branch_id' => null,
            'code' => '115',
            'name' => 'Pajak dibayar dimuka',
        ]);

        Account::create([
            'code' => '121',
            'name' => 'Tanah',
        ]);

        Account::create([
            'code' => '122',
            'name' => 'Bangunan',
        ]);

        Account::create([
            'code' => '123',
            'name' => 'Kendaraan',
        ]);

        Account::create([
            'code' => '124',
            'name' => 'Mesin',
        ]);

        Account::create([
            'code' => '125',
            'name' => 'Peralatan & Inventaris Kantor',
        ]);

        Account::create([
            'code' => '126',
            'name' => 'Peralatan & Inventaris Jaringan',
        ]);

        Account::create([
            'code' => '130',
            'name' => 'Akumulasi Penyusutan Aset Tetap',
        ]);

        Account::create([
            'code' => '211',
            'name' => 'Utang Usaha',
        ]);

        Account::create([
            'code' => '212',
            'name' => 'Utang Deposit Alat',
        ]);

        Account::create([
            'code' => '213',
            'name' => 'Utang Pajak',
        ]);

        Account::create([
            'code' => '214',
            'name' => 'Pendapatan Diterima Dimuka',
        ]);

        Account::create([
            'code' => '215',
            'name' => 'Biaya yang masih harus dibayar',
        ]);

        Account::create([
            'code' => '216',
            'name' => 'Utang lancar lainnya',
        ]);

        Account::create([
            'code' => '221',
            'name' => 'Utang Bank',
        ]);

        Account::create([
            'code' => '222',
            'name' => 'Utang Kendaraan',
        ]);

        Account::create([
            'code' => '223',
            'name' => 'Utang Jangka panjang lainnya',
        ]);

        Account::create([
            'code' => '300',
            'name' => 'Modal',
        ]);

        Account::create([
            'code' => '310',
            'name' => 'Modal Saham',
        ]);

        Account::create([
            'code' => '311',
            'name' => 'Modal Lainnya',
        ]);

        Account::create([
            'code' => '312',
            'name' => 'Saldo laba ditahan',
        ]);

        Account::create([
            'code' => '313',
            'name' => 'Laba bersih periode berjalan',
        ]);

        Account::create([
            'code' => '320',
            'name' => 'Dividen',
        ]);

        Account::create([
            'code' => '401',
            'name' => 'Pendapatan Jasa Layanan Internet',
        ]);

        Account::create([
            'code' => '402',
            'name' => 'Pendapatan Jasa Layanan Jaringan Telekomunikasi',
        ]);

        Account::create([
            'code' => '403',
            'name' => 'Pendapatan Lainnya',
        ]);

        Account::create([
            'code' => '500',
            'name' => 'Beban Pokok Pendapatan',
        ]);

        Account::create([
            'code' => '501',
            'name' => 'Beban Penjualan',
        ]);

        Account::create([
            'code' => '502',
            'name' => 'Beban Karyawan',
        ]);

        Account::create([
            'code' => '503',
            'name' => 'Beban Utilitas',
        ]);

        Account::create([
            'code' => '504',
            'name' => 'Beban Supplies Kantor',
        ]);

        Account::create([
            'code' => '505',
            'name' => 'Beban Angkut/Kirim',
        ]);

        Account::create([
            'code' => '506',
            'name' => 'Beban Perjalanan Dinas',
        ]);

        Account::create([
            'code' => '507',
            'name' => 'Beban Transportasi Kendaraan/Mesin',
        ]);

        Account::create([
            'code' => '508',
            'name' => 'Beban Pemeliharaan Aset',
        ]);

        Account::create([
            'code' => '510',
            'name' => 'Beban Sewa',
        ]);

        Account::create([
            'code' => '511',
            'name' => 'Beban Lain-lain',
        ]);

        Account::create([
            'code' => '512',
            'name' => 'Beban Penyusutan',
        ]);

        Account::create([
            'code' => '513',
            'name' => 'Beban Bunga',
        ]);

        Account::create([
            'code' => '514',
            'name' => 'Beban Pajak Penghasilan',
        ]);

    }
}
