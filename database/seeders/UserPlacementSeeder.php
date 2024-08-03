<?php

namespace Database\Seeders;

use App\Models\UserPlacement;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class UserPlacementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('id_ID');
        UserPlacement::create([
            'code' => $faker->unique()->randomNumber(6),
            'name' => 'Pusat',
        ]);

        UserPlacement::create([
            'code' => $faker->unique()->randomNumber(6),
            'name' => 'Cabang',
        ]);
    }
}
