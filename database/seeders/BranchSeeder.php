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
        $this->dumai();
        $this->duri();
        $this->pkuArifin();
        $this->rohul();
        $this->kampar();
        $this->sawahlunto();
        $this->baturaja();
        $this->karimun();
        $this->payakumbuh();
        $this->siak();
        $this->rohil();
        $this->kuansing();
        $this->solok();
        $this->pkuHangtuah();
        $this->bengkalis();
        $this->kualaTungkal();
        $this->bogorCiomas();


    }


    public function dumai()
    {

        $mainBranch = Branch::create([
            'code' => '101',
            'name' => 'Dumai',
            'address' => 'Jalan Sultan Hasanuddin No. 8A Kelurahan Rimba Sekampung Kecamatan Dumai, Barat, Rimba Sekampung, Kec. Dumai Kota, Kota Dumai',
        ]);


        Branch::create([
            'name' => 'Kantor',
            'address' => '-',
            'parent_id' => $mainBranch->id,
        ]);


        Branch::create([
            'name' => 'Pop Mampu',
            'address' => '-',
            'parent_id' => $mainBranch->id,
        ]);

        Branch::create([
            'name' => 'Pop Pakning',
            'address' => '-',
            'parent_id' => $mainBranch->id,
        ]);

        Branch::create([
            'name' => 'Pop Pelintung',
            'address' => '-',
            'parent_id' => $mainBranch->id,
        ]);


        Branch::create([
            'name' => 'Pop Bukit Timah',
            'address' => '-',
            'parent_id' => $mainBranch->id,
        ]);


        Branch::create([
            'name' => 'Pop Babe',
            'address' => '-',
            'parent_id' => $mainBranch->id,
        ]);

    }


    public function duri(): void
    {
        $mainBranch = Branch::create([
            'code' => '102',
            'name' => 'Duri',
            'address' => 'Jalan Desa Harapan No. 25 , Kelurahan Air Jamban , Kecamatan Mandau Duri.',
        ]);


        Branch::create([
            'name' => 'Kantor',
            'address' => '-',
            'parent_id' => $mainBranch->id,
        ]);


        Branch::create([
            'name' => 'Pop Duri13',
            'address' => '-',
            'parent_id' => $mainBranch->id,
        ]);

        Branch::create([
            'name' => 'Pop Kandis',
            'address' => '-',
            'parent_id' => $mainBranch->id,
        ]);
    }


    public function pkuArifin()
    {
        $mainBranch = Branch::create([
            'code' => '103',
            'name' => 'Pekanbaru Arifin',
            'address' => 'Jalan Arifin Ahmad No. 113 E, Kelurahan Sidomulyo Timur Kecamatan Marpoyan Damai.',
        ]);

        Branch::create([
            'name' => 'Pop Kubang',
            'address' => '-',
            'parent_id' => $mainBranch->id,
        ]);

        Branch::create([
            'name' => 'Pop Rengat',
            'address' => '-',
            'parent_id' => $mainBranch->id,
        ]);

        Branch::create([
            'name' => 'Kantor',
            'address' => '-',
            'parent_id' => $mainBranch->id,
        ]);


        Branch::create([
            'name' => 'Gudang Pusat',
            'address' => '-',
            'parent_id' => $mainBranch->id,
        ]);

        Branch::create([
            'name' => 'Pop Rumbai',
            'address' => '-',
            'parent_id' => $mainBranch->id,
        ]);
    }


    public function rohul(): void
    {
        $mainBranch = Branch::create([
            'code' => '104',
            'name' => 'Rohul',
            'address' => 'Jalan Tuanku Tambusai, Desa Pematang Barangan, Kecamatan Rambah / Jalan Durian Sebatang, Depan SDN 003.',
        ]);

        Branch::create([
            'name' => 'Kantor',
            'address' => '-',
            'parent_id' => $mainBranch->id,
        ]);

        Branch::create([
            'name' => 'Pop Ujung Batu',
            'address' => '-',
            'parent_id' => $mainBranch->id,
        ]);

        Branch::create([
            'name' => 'Pop Kabun',
            'address' => '-',
            'parent_id' => $mainBranch->id,
        ]);

        Branch::create([
            'name' => 'Pop Tambusai',
            'address' => '-',
            'parent_id' => $mainBranch->id,
        ]);
    }

    private function kampar(): void
    {

        $mainBranch = Branch::create([
            'code' => '105',
            'name' => 'Kampar',
            'address' => 'Jalan Sisingamangaraja No.23 Kelurahan Langgini, Kecamatan Bangkinang.',
        ]);


        Branch::create([
            'name' => 'Kantor',
            'address' => '-',
            'parent_id' => $mainBranch->id,
        ]);

        Branch::create([
            'name' => 'Pop Silam',
            'address' => '-',
            'parent_id' => $mainBranch->id,
        ]);

        Branch::create([
            'name' => 'Pop Danau',
            'address' => '-',
            'parent_id' => $mainBranch->id,
        ]);

        Branch::create([
            'name' => 'Pop SP',
            'address' => '-',
            'parent_id' => $mainBranch->id,
        ]);
    }

    private function sawahlunto()
    {
        $mainBranch = Branch::create([
            'code' => '106',
            'name' => 'Sawahlunto',
            'address' => 'Jl. Ahmad yani Kec. Ahmad Yani, Kota Sawahlunto, Sumatera Barat',
        ]);


        Branch::create([
            'name' => 'Kantor',
            'address' => '-',
            'parent_id' => $mainBranch->id,
        ]);

        Branch::create([
            'name' => 'Pop Talawi',
            'address' => '-',
            'parent_id' => $mainBranch->id,
        ]);
    }

    private function baturaja(): void
    {
        $mainBranch = Branch::create([
            'code' => '107',
            'name' => 'Oku baturaja',
            'address' => 'Jalan Jendral Ahmad YanI Kelurahan Baturaja Lama Kecamatan Baturaja Timur.',
        ]);

        Branch::create([
            'name' => 'Kantor',
            'address' => '-',
            'parent_id' => $mainBranch->id,
        ]);
    }

    private function karimun(): void
    {
        $mainBranch = Branch::create([
            'code' => '108',
            'name' => 'Karimun',
            'address' => 'Jalan Ahmad Yani No. 17, Kelurahan Baran Timur, Kecamatan Meral.',
        ]);

        Branch::create([
            'name' => 'Kantor',
            'address' => '-',
            'parent_id' => $mainBranch->id,
        ]);
    }

    private function payakumbuh(): void
    {
        $mainBranch = Branch::create([
            'code' => '110',
            'name' => 'Payakumbuh',
            'address' => 'JL. Veteran Parak NO. 15 c kelurahan batung kecamatan, Payakumbuh barat.',
        ]);

        Branch::create([
            'name' => 'Kantor',
            'address' => '-',
            'parent_id' => $mainBranch->id,
        ]);
    }

    private function siak(): void
    {
        $mainBranch = Branch::create([
            'code' => '111',
            'name' => 'Siak',
            'address' => 'Jalan. Tengku buang asmara, Kelurahan Suak merambai, Kecamatan Bunga raya, Kabupaten Siak.',
        ]);

        Branch::create([
            'name' => 'Kantor',
            'address' => '-',
            'parent_id' => $mainBranch->id,
        ]);

        Branch::create([
            'name' => 'Pop Minas',
            'address' => '-',
            'parent_id' => $mainBranch->id,
        ]);
    }

    private function rohil(): void
    {
        $mainBranch = Branch::create([
            'code' => '112',
            'name' => 'Rohil',
            'address' => 'Jalan Kecamatan No 5, Kelurahan Bagan punak, Kecamatan Bangko, Kabupaten Rokan hilir',
        ]);


        Branch::create([
            'name' => 'Kantor',
            'address' => '-',
            'parent_id' => $mainBranch->id,
        ]);
    }

    private function kuansing(): void
    {
        $mainBranch = Branch::create([
            'code' => '113',
            'name' => 'Kuansing',
            'address' => 'Jalan Kecamatan No 5, Kelurahan Bagan punak, Kecamatan Bangko, Kabupaten Rokan hilir',
        ]);

        Branch::create([
            'name' => 'Kantor',
            'address' => '-',
            'parent_id' => $mainBranch->id,
        ]);

    }

    private function solok(): void
    {
        $mainBranch = Branch::create([
            'code' => '114',
            'name' => 'Solok',
            'address' => 'Jl. Syeck Kukut (Belakang Terminal Lama) No. 8A, Kelurahan Tanjung Paku, Kecamatan Tanjung Harapan – Solok',
        ]);

        Branch::create([
            'name' => 'Kantor',
            'address' => '-',
            'parent_id' => $mainBranch->id,
        ]);

    }

    private function pkuHangtuah(): void
    {
        $mainBranch = Branch::create([
            'code' => '115',
            'name' => 'Pekanbaru Hangtuah',
            'address' => 'Jl. Hangtuah, Ujung, Kec. Tenayan Raya, Kota Pekanbaru',
        ]);

        Branch::create([
            'name' => 'Kantor',
            'address' => '-',
            'parent_id' => $mainBranch->id,
        ]);
    }

    private function bengkalis()
    {
        $mainBranch = Branch::create([
            'code' => '116',
            'name' => 'Bengkalis',
            'address' => 'Gg. Sahabat, Rimba Sekampung, Kec. Bengkalis, Kabupaten Bengkalis',
        ]);

        Branch::create([
            'name' => 'Kantor',
            'address' => '-',
            'parent_id' => $mainBranch->id,
        ]);
    }

    private function kualaTungkal(): void
    {
        $mainBranch = Branch::create([
            'code' => '117',
            'name' => 'Kuala tungkal',
            'address' => 'Jl. Diponegoro RT 015 Kel. Tungkal Harapan, Kec. Tungkal Hilir',
        ]);
        Branch::create([
            'name' => 'Kantor',
            'address' => '-',
            'parent_id' => $mainBranch->id,
        ]);
    }

    private function bogorCiomas(): void
    {

        $mainBranch = Branch::create([
            'code' => '118',
            'name' => 'Bogor Ciomas',
            'address' => 'Jl. Villa Ciomas, Ciomas Rahayu, Kec. Ciomas, Kabupaten Bogor, Jawa Barat',
        ]);


        Branch::create([
            'name' => 'Kantor',
            'address' => '-',
            'parent_id' => $mainBranch->id,
        ]);
    }


}
