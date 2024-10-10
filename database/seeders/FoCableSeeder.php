<?php

namespace Database\Seeders;

use App\Models\FOCable;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FoCableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        FOCable::create([
            'branch_id' => 1,
            'segment_id' => 1,
            'classification' => 'Backbone',
            'cable_placement' => 'Udara',
            'cable_address' => 'asdasdsadasdasdasdasd',
            'total_core' => 1,
            'starting_point_lat' => 123123,
            'starting_point_long' => 123213213,
            'ending_point_lat' => 123213,
            'ending_point_long' => 123213,
            'length' => 123213,
            'cut_off_date' => Carbon::now(),
        ]);
    }
}
