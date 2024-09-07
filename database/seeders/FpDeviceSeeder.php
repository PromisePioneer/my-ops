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
            'name' => 'Dumai (Main)',
            'serial_number' => 'AEWD233960062',
        ]);

        FpDevice::create([
            'branch_id' => $this->branch->where('name', 'Dumai')->first()->id,
            'name' => 'Dumai (Mampu)',
            'serial_number' => 'CKEB222460363',
        ]);

        FpDevice::create([
            'branch_id' => $this->branch->where('name', 'Dumai')->first()->id,
            'name' => 'Dumai (Babe)',
            'serial_number' => 'CKEB222461192',
        ]);

        FpDevice::create([
            'branch_id' => $this->branch->where('name', 'Duri')->first()->id,
            'name' => 'Duri (Harapan)',
            'serial_number' => 'CKEB232360172',
        ]);

        FpDevice::create([
            'branch_id' => $this->branch->where('name', 'Duri')->first()->id,
            'name' => 'Duri (Duri13)',
            'serial_number' => 'CKEB221060441',
        ]);

        FpDevice::create([
            'branch_id' => $this->branch->where('name', 'Duri')->first()->id,
            'name' => 'Duri (Kandis)',
            'serial_number' => 'CKEB223360632',
        ]);

        FpDevice::create([
            'branch_id' => $this->branch->where('name', 'Duri')->first()->id,
            'name' => 'Duri (Kandis)',
            'serial_number' => 'CKEB223360632',
        ]);

        FpDevice::create([
            'branch_id' => $this->branch->where('name', 'Pekanbaru Arifin')->first()->id,
            'name' => 'Pekanbaru Arifin',
            'serial_number' => 'BWXP183361136',
        ]);

        FpDevice::create([
            'branch_id' => $this->branch->where('name', 'Siak')->first()->id,
            'name' => 'Siak (Minas)',
            'serial_number' => 'CKEB224860121',
        ]);

        FpDevice::create([
            'branch_id' => $this->branch->where('name', 'Rohul')->first()->id,
            'name' => 'Rohul (Pasir)',
            'serial_number' => 'CKEB233160985',
        ]);

        FpDevice::create([
            'branch_id' => $this->branch->where('name', 'Rohul')->first()->id,
            'name' => 'Rohul (Ujung)',
            'serial_number' => 'BWXP194360836',
        ]);

        FpDevice::create([
            'branch_id' => $this->branch->where('name', 'Rohul')->first()->id,
            'name' => 'Rohul (Kabun)',
            'serial_number' => 'CKEB233160985',
        ]);

        FpDevice::create([
            'branch_id' => $this->branch->where('name', 'Sawahlunto')->first()->id,
            'name' => 'Sawahlunto (P.Kapur)',
            'serial_number' => 'BWXP203960476',
        ]);

        FpDevice::create([
            'branch_id' => $this->branch->where('name', 'Oku baturaja')->first()->id,
            'name' => 'OKU (Baturaja)',
            'serial_number' => 'CKEB223360638',
        ]);

        FpDevice::create([
            'branch_id' => $this->branch->where('name', 'Karimun')->first()->id,
            'name' => 'Karimun (Baran I)',
            'serial_number' => 'CKEB223360638',
        ]);

        FpDevice::create([
            'branch_id' => $this->branch->where('name', 'Payakumbuh')->first()->id,
            'name' => 'Karimun (Baran I)',
            'serial_number' => 'BWXP211160197',
        ]);

        FpDevice::create([
            'branch_id' => $this->branch->where('name', 'Kampar')->first()->id,
            'name' => 'BWXP191660449',
            'serial_number' => 'BWXP191660449',
        ]);

        FpDevice::create([
            'branch_id' => $this->branch->where('name', 'Rohil')->first()->id,
            'name' => 'Rohil (Bagan)',
            'serial_number' => 'BWXP191660449',
        ]);

        FpDevice::create([
            'branch_id' => $this->branch->where('name', 'Kuansing')->first()->id,
            'name' => 'Kuansing (Sei Jering)',
            'serial_number' => 'CKEB220760143',
        ]);

        FpDevice::create([
            'branch_id' => $this->branch->where('name', 'Solok')->first()->id,
            'name' => 'Solok (Tj. Harapan)',
            'serial_number' => 'CKEB220760143',
        ]);

//        FpDevice::create([
//            'branch_id'=> $this->branch->where('name', '')
//        ])
    }
}
