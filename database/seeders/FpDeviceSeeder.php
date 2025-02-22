<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\FpDevice;
use Illuminate\Database\Seeder;

class FpDeviceSeeder extends Seeder
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
        FpDevice::create([
            'branch_id' => $this->branch->where('name', 'Dumai')->first()->id,
            'name' => 'Kantor',
            'ip_address' => '103.102.248.163',
            'serial_number' => 'AEWD233960062',
        ]);

        FpDevice::create([
            'branch_id' => $this->branch->where('name', 'Dumai')->first()->id,
            'name' => 'Pop Babe',
            'ip_address' => '103.102.248.134',
            'serial_number' => 'NHZ4243602069',
        ]);

        FpDevice::create([
            'branch_id' => $this->branch->where('name', 'Duri')->first()->id,
            'name' => 'Kantor',
            'ip_address' => '103.211.160.26',
            'serial_number' => 'CKEB232360172',
        ]);

        FpDevice::create([
            'branch_id' => $this->branch->where('name', 'Duri')->first()->id,
            'name' => 'Pop Duri13',
            'ip_address' => '103.211.160.231',
            'serial_number' => 'CKEB232360172',
        ]);

        FpDevice::create([
            'branch_id' => $this->branch->where('name', 'Duri')->first()->id,
            'name' => 'Pop Kandis',
            'ip_address' => '103.211.160.247',
            'serial_number' => 'CKEB223360632',
        ]);

        FpDevice::create([
            'branch_id' => $this->branch->where('name', 'Pekanbaru Arifin')->first()->id,
            'name' => 'Kantor',
            'ip_address' => '103.41.255.216',
            'serial_number' => 'BWXP183361136'
        ]);

        FpDevice::create([
            'branch_id' => $this->branch->where('name', 'Siak')->first()->id,
            'name' => 'Pop Minas',
            'ip_address' => '103.41.255.248',
            'serial_number' => 'CKEB224860121',
        ]);


        FpDevice::create([
            'branch_id' => $this->branch->where('name', 'Rohul')->first()->id,
            'name' => 'Kantor',
            'ip_address' => '103.16.133.201',
            'serial_number' => 'CKEB233160985',
        ]);


        FpDevice::create([
            'branch_id' => $this->branch->where('name', 'Rohul')->first()->id,
            'name' => 'Pop Ujung Batu',
            'ip_address' => '103.16.133.116',
            'serial_number' => 'BWXP194360836',
        ]);


        FpDevice::create([
            'branch_id' => $this->branch->where('name', 'Rohul')->first()->id,
            'name' => 'Pop Kabun',
            'ip_address' => '103.16.133.117',
            'serial_number' => 'CKEB223360674',
        ]);

        FpDevice::create([
            'branch_id' => $this->branch->where('name', 'Kampar')->first()->id,
            'name' => 'Kantor',
            'ip_address' => '103.16.133.219',
            'serial_number' => 'BWXP191660449'
        ]);


        FpDevice::create([
            'branch_id' => $this->branch->where('name', 'Kampar')->first()->id,
            'name' => 'Pop Danau',
            'ip_address' => '103.16.133.181',
            'serial_number' => 'CKEB223360674'
        ]);

        FpDevice::create([
            'branch_id' => $this->branch->where('name', 'Sawahlunto')->first()->id,
            'name' => 'Kantor',
            'ip_address' => '103.102.248.33',
            'serial_number' => 'BWXP203960476'
        ]);


        FpDevice::create([
            'branch_id' => $this->branch->where('name', 'Oku baturaja')->first()->id,
            'name' => 'Kantor',
            'ip_address' => '103.102.248.64',
            'serial_number' => 'CKEB223360638'
        ]);


        FpDevice::create([
            'branch_id' => $this->branch->where('name', 'Karimun')->first()->id,
            'name' => 'Kantor',
            'ip_address' => '203.153.22.224',
            'serial_number' => 'BWXP205260435',
        ]);

        FpDevice::create([
            'branch_id' => $this->branch->where('name', 'Payakumbuh')->first()->id,
            'name' => 'Kantor',
            'ip_address' => '103.102.248.50',
            'serial_number' => 'BWXP211160197',
        ]);


        FpDevice::create([
            'branch_id' => $this->branch->where('name', 'Siak')->first()->id,
            'name' => 'Kantor',
            'ip_address' => '103.116.13.192',
            'serial_number' => 'BWXP212260444'
        ]);


        FpDevice::create([
            'branch_id' => $this->branch->where('name', 'Rohil')->first()->id,
            'name' => 'Kantor',
            'ip_address' => '103.102.248.112',
            'serial_number' => 'BWXP212260422'
        ]);

        FpDevice::create([
            'branch_id' => $this->branch->where('name', 'Kuansing')->first()->id,
            'name' => 'Kantor',
            'ip_address' => '103.102.248.112',
            'serial_number' => 'CKEB220760143'
        ]);


        FpDevice::create([
            'branch_id' => $this->branch->where('name', 'Solok')->first()->id,
            'name' => 'Kantor',
            'ip_address' => '103.102.132.222',
            'serial_number' => 'CKEB222460569'
        ]);

        FpDevice::create([
            'branch_id' => $this->branch->where('name', 'Pekanbaru Arifin')->first()->id,
            'name' => 'Gudang Pusat',
            'ip_address' => '103.141.255.212',
            'serial_number' => 'CKEB232360470'
        ]);

        FpDevice::create([
            'branch_id' => $this->branch->where('name', 'Pekanbaru Hangtuah')->first()->id,
            'name' => 'Kantor',
            'ip_address' => '103.141.255.88',
            'serial_number' => 'CKEB232260656',
        ]);


        FpDevice::create([
            'branch_id' => $this->branch->where('name', 'Dumai')->first()->id,
            'name' => 'Pop Pelintung',
            'ip_address' => '103.102.248.254',
            'serial_number' => 'CKEB232260655',
        ]);


        FpDevice::create([
            'branch_id' => $this->branch->where('name', 'Sawahlunto')->first()->id,
            'name' => 'Pop Talawi',
            'ip_address' => '103.102.248.254',
            'serial_number' => 'CKEB232260660',
        ]);

        FpDevice::create([
            'branch_id' => $this->branch->where('name', 'Bengkalis')->first()->id,
            'name' => 'Kantor',
            'ip_address' => '203.18.39.224',
            'serial_number' => 'CKEB233160127',
        ]);

        FpDevice::create([
            'branch_id' => $this->branch->where('name', 'Dumai')->first()->id,
            'name' => 'Pop Bukit Timah',
            'ip_address' => '103.103.248.253',
            'serial_number' => 'CKEB233160113',
        ]);

        FpDevice::create([
            'branch_id' => $this->branch->where('name', 'Kuala Tungkal')->first()->id,
            'name' => 'Kantor',
            'ip_address' => '210.87.122.240',
            'serial_number' => 'CKEB233160133'
        ]);


        FpDevice::create([
            'branch_id' => $this->branch->where('name', 'Pekanbaru Arifin')->first()->id,
            'name' => 'Kantor',
            'ip_address' => '103.141.255.223',
            'serial_number' => 'NHZ435100465'
        ]);

        FpDevice::create([
            'branch_id' => $this->branch->where('name', 'Rohul')->first()->id,
            'name' => 'Tambusai',
            'ip_address' => '103.141.255.194',
            'serial_number' => 'CKEB233160128'
        ]);

        FpDevice::create([
            'branch_id' => $this->branch->where('name', 'Dumai')->first()->id,
            'name' => 'Pop Pelintung',
            'ip_address' => '103.177.218.10',
            'serial_number' => 'NHZ435100464'
        ]);

        FpDevice::create([
            'branch_id' => $this->branch->where('name', 'Bogor Ciomas')->first()->id,
            'name' => 'Kantor',
            'ip_address' => '160.22.177.248',
            'serial_number' => 'NHZ4235100461',
        ]);


        FpDevice::create([
            'branch_id' => $this->branch->where('name', 'Kampar')->first()->id,
            'name' => 'Pop SP',
            'ip_address' => '103.16.133.183',
            'serial_number' => 'NHZ424230087',
        ]);

        FpDevice::create([
            'branch_id' => $this->branch->where('name', 'Pekanbaru Arifin')->first()->id,
            'name' => 'Pop Kubang',
            'ip_address' => '103.141.255.222',
            'serial_number' => 'NHZ4243000504'
        ]);


        FpDevice::create([
            'branch_id' => $this->branch->where('name', 'Pekanbaru Arifin')->first()->id,
            'name' => 'Pop Rengat',
            'ip_address' => '103.177.218.162',
            'serial_number' => 'NHZ4242300153'
        ]);

        FpDevice::create([
            'branch_id' => $this->branch->where('name', 'Dumai')->first()->id,
            'name' => 'Pop Mampu',
            'ip_address' => '103.102.248.248',
            'serial_number' => 'NHZ4243603023'
        ]);


    }
}
