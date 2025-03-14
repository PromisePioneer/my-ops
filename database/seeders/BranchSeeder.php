<?php

namespace Database\Seeders;

use App\Models\Master\Common\Branch;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class BranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        //Branch
        Branch::create([
            'code' => '101',
            'name' => 'Dumai',
            'address' => 'Jalan Sultan Hasanuddin No. 8A Kelurahan Rimba Sekampung Kecamatan Dumai, Barat, Rimba Sekampung, Kec. Dumai Kota, Kota Dumai',
        ]);

        Branch::create([
            'code' => '102',
            'name' => 'Duri',
            'address' => 'Jalan Desa Harapan No. 25 , Kelurahan Air Jamban , Kecamatan Mandau Duri.',
        ]);

        Branch::create([
            'code' => '103',
            'name' => 'Pekanbaru Arifin',
            'address' => 'Jalan Arifin Ahmad No. 113 E, Kelurahan Sidomulyo Timur Kecamatan Marpoyan Damai.',
        ]);

        Branch::create([
            'code' => '104',
            'name' => 'Rohul',
            'address' => 'Jalan Tuanku Tambusai, Desa Pematang Barangan, Kecamatan Rambah / Jalan Durian Sebatang, Depan SDN 003.',
        ]);

        Branch::create([
            'code' => '105',
            'name' => 'Kampar',
            'address' => 'Jalan Sisingamangaraja No.23 Kelurahan Langgini, Kecamatan Bangkinang.',
        ]);

        Branch::create([
            'code' => '106',
            'name' => 'Sawahlunto',
            'address' => 'Jl. Ahmad yani Kec. Ahmad Yani, Kota Sawahlunto, Sumatera Barat',
        ]);

        Branch::create([
            'code' => '107',
            'name' => 'Oku baturaja',
            'address' => 'Jalan Jendral Ahmad YanI Kelurahan Baturaja Lama Kecamatan Baturaja Timur.',
        ]);

        Branch::create([
            'code' => '108',
            'name' => 'Karimun',
            'address' => 'Jalan Ahmad Yani No. 17, Kelurahan Baran Timur, Kecamatan Meral.',
        ]);

        Branch::create([
            'code' => '110',
            'name' => 'Payakumbuh',
            'address' => 'JL. Veteran Parak NO. 15 c kelurahan batung kecamatan, Payakumbuh barat.',
        ]);

        Branch::create([
            'code' => '111',
            'name' => 'Siak',
            'address' => 'Jalan. Tengku buang asmara, Kelurahan Suak merambai, Kecamatan Bunga raya, Kabupaten Siak.',
        ]);

        Branch::create([
            'code' => '112',
            'name' => 'Rohil',
            'address' => 'Jalan Kecamatan No 5, Kelurahan Bagan punak, Kecamatan Bangko, Kabupaten Rokan hilir',
        ]);

        Branch::create([
            'code' => '113',
            'name' => 'Kuansing',
            'address' => 'Jalan Kecamatan No 5, Kelurahan Bagan punak, Kecamatan Bangko, Kabupaten Rokan hilir',
        ]);

        Branch::create([
            'code' => '114',
            'name' => 'Solok',
            'address' => 'Jl. Syeck Kukut (Belakang Terminal Lama) No. 8A, Kelurahan Tanjung Paku, Kecamatan Tanjung Harapan – Solok',
        ]);

        Branch::create([
            'code' => '115',
            'name' => 'Pekanbaru Hangtuah',
            'address' => 'Jl. Hangtuah, Ujung, Kec. Tenayan Raya, Kota Pekanbaru',
        ]);

        Branch::create([
            'code' => '116',
            'name' => 'Bengkalis',
            'address' => 'Gg. Sahabat, Rimba Sekampung, Kec. Bengkalis, Kabupaten Bengkalis',
        ]);

        Branch::create([
            'code' => '117',
            'name' => 'Kuala tungkal',
            'address' => 'Jl. Diponegoro RT 015 Kel. Tungkal Harapan, Kec. Tungkal Hilir',
        ]);

        Branch::create([
            'code' => '118',
            'name' => 'Bogor Ciomas',
            'address' => 'Jl. Villa Ciomas, Ciomas Rahayu, Kec. Ciomas, Kabupaten Bogor, Jawa Barat',
        ]);
    }
}
