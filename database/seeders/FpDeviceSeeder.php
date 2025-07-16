<?php

namespace Database\Seeders;

use AllowDynamicProperties;
use App\Models\FpDevice;
use App\Models\Master\Common\Branch;
use Illuminate\Database\Seeder;

#[AllowDynamicProperties] class FpDeviceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function __construct()
    {
        $this->branch = new Branch();
    }

    public function run(): void
    {


        $this->dumai();
        $this->duri();
        $this->pkuArifin();
        $this->siak();
        $this->rohul();
        $this->kampar();
        $this->sawalunto();
        $this->baturaja();
        $this->karimun();
        $this->rohil();
        $this->kuansing();
        $this->pkuHangtuah();
        $this->bengkalis();
        $this->kualaTungkal();
        $this->bogorCiomas();
        $this->payakumbuh();


    }


    public function dumai(): void
    {
        $branch = $this->branch->with('children')->where('name', 'Dumai')->first();

        FpDevice::create([
            'branch_id' => $branch->children()->where('name', 'Kantor')->first()->id,
            'ip_address' => '103.102.248.163',
            'serial_number' => 'AEWD233960062',
        ]);

        FpDevice::create([
            'branch_id' => $branch->children()->where('name', 'Pop Babe')->first()->id,
            'ip_address' => '103.102.248.134',
            'serial_number' => 'NHZ4243602069',
        ]);


        FpDevice::create([
            'branch_id' => $branch->children()->where('name', 'Pop Pelintung')->first()->id,
            'ip_address' => '103.102.248.254',
            'serial_number' => 'CKEB232260655',
        ]);


        FpDevice::create([
            'branch_id' => $branch->children()->where('name', 'Pop Bukit Timah')->first()->id,
            'ip_address' => '103.103.248.253',
            'serial_number' => 'CKEB233160113',
        ]);


        FpDevice::create([
            'branch_id' => $branch->children()->where('name', 'Pop Mampu')->first()->id,
            'ip_address' => '103.102.248.248',
            'serial_number' => 'NHZ4243603023'
        ]);
    }


    public function duri(): void
    {

        $branch = $this->branch->with('children')->where('name', 'Duri')->first();

        FpDevice::create([
            'branch_id' => $branch->children()->where('name', 'Kantor')->first()->id,
            'ip_address' => '103.211.160.26',
            'serial_number' => 'CKEB232360172',
        ]);


        FpDevice::create([
            'branch_id' => $branch->children()->where('name', 'Pop Duri13')->first()->id,
            'ip_address' => '103.211.160.231',
            'serial_number' => 'CKEB232360172',
        ]);

        FpDevice::create([
            'branch_id' => $branch->children()->first()->where('name', 'Pop Kandis')->first()->id,
            'ip_address' => '103.211.160.247',
            'serial_number' => 'CKEB223360632',
        ]);


    }

    private function pkuArifin(): void
    {

        $branch = $this->branch->with('children')->where('name', 'Pekanbaru Arifin')->first();

        FpDevice::create([
            'branch_id' => $branch->children()->where('name', 'Kantor')->first()->id,
            'ip_address' => '103.41.255.216',
            'serial_number' => 'BWXP183361136'
        ]);


        FpDevice::create([
            'branch_id' => $branch->children()->where('name', 'Gudang Pusat')->first()->id,
            'ip_address' => '103.141.255.212',
            'serial_number' => 'CKEB232360470'
        ]);
        FpDevice::create([
            'branch_id' => $branch->children()->where('name', 'Pop Kubang')->first()->id,
            'ip_address' => '103.141.255.222',
            'serial_number' => 'NHZ4243000504'
        ]);

        FpDevice::create([
            'branch_id' => $branch->children()->where('name', 'Pop Rengat')->first()->id,
            'ip_address' => '103.177.218.162',
            'serial_number' => 'NHZ4242300153'
        ]);


    }


    public function siak(): void
    {
        $branch = $this->branch->with('children')->where('name', 'Siak')->first();

        FpDevice::create([
            'branch_id' => $branch->children()->where('name', 'Pop Minas')->first()->id,
            'ip_address' => '103.41.255.248',
            'serial_number' => 'CKEB224860121',
        ]);

        FpDevice::create([
            'branch_id' => $branch->children()->where('name', 'Kantor')->first()->id,
            'ip_address' => '103.116.13.192',
            'serial_number' => 'BWXP212260444'
        ]);
    }


    public function rohul(): void
    {


        $branch = $this->branch->with('children')->where('name', 'Rohul')->first();

        FpDevice::create([
            'branch_id' => $branch->children()->where('name', 'Kantor')->first()->id,
            'ip_address' => '103.16.133.201',
            'serial_number' => 'CKEB233160985',
        ]);


        FpDevice::create([
            'branch_id' => $branch->children()->where('name', 'Pop Ujung Batu')->first()->id,
            'ip_address' => '103.16.133.116',
            'serial_number' => 'BWXP194360836',
        ]);

        FpDevice::create([
            'branch_id' => $branch->children()->where('name', 'Pop Kabun')->first()->id,
            'ip_address' => '103.16.133.117',
            'serial_number' => 'CKEB223360674',
        ]);


        FpDevice::create([
            'branch_id' => $branch->children()->where('name', 'Pop Tambusai')->first()->id,
            'ip_address' => '103.141.255.194',
            'serial_number' => 'CKEB233160128'
        ]);
    }


    public function kampar(): void
    {
        $branch = $this->branch->with('children')->where('name', 'Kampar')->first();
        FpDevice::create([
            'branch_id' => $branch->children()->where('name', 'Kantor')->first()->id,
            'ip_address' => '103.16.133.219',
            'serial_number' => 'BWXP191660449'
        ]);

        FpDevice::create([
            'branch_id' => $branch->children()->where('name', 'Pop Danau')->first()->id,
            'ip_address' => '103.16.133.181',
            'serial_number' => 'CKEB223360674'
        ]);


        FpDevice::create([
            'branch_id' => $branch->children()->where('name', 'Pop SP')->first()->id,
            'ip_address' => '103.16.133.183',
            'serial_number' => 'NHZ424230087',
        ]);
    }


    public function sawalunto(): void
    {

        $branch = $this->branch->with('children')->where('name', 'Sawahlunto')->first();

        FpDevice::create([
            'branch_id' => $branch->children()->where('name', 'Kantor')->first()->id,
            'ip_address' => '103.102.248.33',
            'serial_number' => 'BWXP203960476'
        ]);

        FpDevice::create([
            'branch_id' => $this->branch->first()->where('name', 'Pop Talawi')->first()->id,
            'ip_address' => '103.102.248.254',
            'serial_number' => 'CKEB232260660',
        ]);
    }


    public function baturaja(): void
    {
        $branch = $this->branch->with('children')->where('name', 'Oku Baturaja')->first();

        FpDevice::create([
            'branch_id' => $branch->children()->where('name', 'Kantor')->first()->id,
            'ip_address' => '103.102.248.64',
            'serial_number' => 'CKEB223360638'
        ]);
    }


    public function karimun(): void
    {

        $branch = $this->branch->with('children')->where('name', 'Karimun')->first();

        FpDevice::create([
            'branch_id' => $branch->children()->where('name', 'Kantor')->first()->id,
            'ip_address' => '203.153.22.224',
            'serial_number' => 'BWXP205260435',
        ]);

    }


    public function payakumbuh(): void
    {

        $branch = $this->branch->with('children')->where('name', 'Payakumbuh')->first();

        FpDevice::create([
            'branch_id' => $branch->children()->where('name', 'Kantor')->first()->id,
            'ip_address' => '103.102.248.50',
            'serial_number' => 'BWXP211160197',
        ]);
    }

    private function rohil(): void
    {
        $branch = $this->branch->with('children')->where('name', 'Rohil')->first();
        FpDevice::create([
            'branch_id' => $branch->children()->where('name', 'Kantor')->first()->id,
            'ip_address' => '103.102.248.112',
            'serial_number' => 'BWXP212260422'
        ]);
    }


    public function kuansing(): void
    {
        $branch = $this->branch->with('children')->where('name', 'Kuansing')->first();
        FpDevice::create([
            'branch_id' => $branch->children()->where('name', 'Kantor')->first()->id,
            'ip_address' => '103.102.248.112',
            'serial_number' => 'CKEB220760143'
        ]);
    }

    public function solok(): void
    {
        $branch = $this->branch->with('children')->where('name', 'Solok')->first();
        FpDevice::create([
            'branch_id' => $branch->children()->where('name', 'Kantor')->first()->id,
            'ip_address' => '103.102.132.222',
            'serial_number' => 'CKEB222460569'
        ]);
    }

    private function pkuHangtuah(): void
    {
        $branch = $this->branch->with('children')->where('name', 'Pekanbaru Hangtuah')->first();
        FpDevice::create([
            'branch_id' => $branch->children()->where('name', 'Kantor')->first()->id,
            'ip_address' => '103.141.255.88',
            'serial_number' => 'CKEB232260656',
        ]);
    }

    private function bengkalis()
    {
        $branch = $this->branch->with('children')->where('name', 'Bengkalis')->first();

        FpDevice::create([
            'branch_id' => $branch->children()->where('name', 'Kantor')->first()->id,
            'ip_address' => '203.18.39.224',
            'serial_number' => 'CKEB233160127',
        ]);
    }

    private function kualaTungkal(): void
    {

        $branch = $this->branch->with('children')->where('name', 'Kuala Tungkal')->first();
        FpDevice::create([
            'branch_id' => $branch->children->where('name', 'Kantor')->first()->id,
            'ip_address' => '210.87.122.240',
            'serial_number' => 'CKEB233160133'
        ]);
    }

    private function bogorCiomas(): void
    {
        $branch = $this->branch->with('children')->where('name', 'Bogor Ciomas')->first();
        FpDevice::create([
            'branch_id' => $branch->children()->where('name', 'Kantor')->first()->id,
            'ip_address' => '160.22.177.248',
            'serial_number' => 'NHZ4235100461',
        ]);
    }


}
