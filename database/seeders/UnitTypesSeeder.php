<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UnitTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('unit_types')->insert([
            'name' => 'Kg',
        ]);


        DB::table('unit_types')->insert([
            'name' => 'Meter',
        ]);

        DB::table('unit_types')->insert([
            'name' => 'PCS',
        ]);

        DB::table('unit_types')->insert([
            'name' => 'ROLL',
        ]);


        DB::table('unit_types')->insert([
            'name' => 'Haspel',
        ]);

        DB::table('unit_types')->insert([
            'name' => 'Gulung',
        ]);

        DB::table('unit_types')->insert([
            'name' => 'Kotak',
        ]);

        DB::table('unit_types')->insert([
            'name' => 'Unit',
        ]);

    }
}
