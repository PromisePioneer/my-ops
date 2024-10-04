<?php

namespace Database\Seeders;

use App\Models\FOCable;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class FOCableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        FOCable::create([
            'segment_id' => 'M01-AX12-PPY',
            'classification' => 'Akses',
            'cable_placement' => 'Udara',
            'total_core' => '12',
            'cable_address' => 'Jl. Pepaya, Rimba Sekampung',
            'starting_point_lat' => '1.673960',
            'starting_point_long' => '101.441873',
            'ending_point_lat' => '1.672304',
            'ending_point_long' => '101.435957',
            'length' => '743',
            'cut_off_date' => Carbon::now(),
        ]);
    }
}
