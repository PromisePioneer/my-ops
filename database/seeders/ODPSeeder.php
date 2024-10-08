<?php

namespace Database\Seeders;

use App\Models\ODP;
use App\Models\ODPArea;
use Illuminate\Database\Seeder;

class ODPSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ODP::create([
            'area_id' => ODPArea::first()->id,
            'name' => fake()->unique()->name(),
            'classification' => 'Turunan',
            'passive_splitter' => 'ODP',
            'lat' => 1.683456,
            'long' => 101.441150,
            'max_capacity' => 100,
            'used_capacity' => 1,
            'cut_off' => "MEI",

        ]);


        ODP::create([
            'area_id' => ODPArea::first()->id,
            'name' => fake()->unique()->name(),
            'classification' => 'Turunan',
            'passive_splitter' => 'ODP',
            'lat' => 1.684351,
            'long' => 101.443132,
            'max_capacity' => 100,
            'used_capacity' => 1,
            'cut_off' => "MEI",
        ]);
    }
}
