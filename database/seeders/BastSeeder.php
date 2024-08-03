<?php

namespace Database\Seeders;

use App\Models\Bast;
use Carbon\Carbon;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class BastSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker =  Faker::create('id_ID');

        Bast::create([
            'branch_id' => 1,
            'fab_id' => null,
            'bast_number' => "061/MY-PKUAA/III/2024",
            'contact_id' => $faker->numberBetween(1, 10),
            'date' => Carbon::now(),
            'first_party_identity_name' => "Muhammad Rizki",
            'first_party_position' => 'Fullstack Developer',
            'objective' => $faker->text(),
            'file' => null,
            'created_by' => 1
        ]);
    }
}
