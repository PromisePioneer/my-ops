<?php

namespace Database\Seeders;

use App\Models\Branch;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class BranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('id_ID');

        for ($i = 0; $i < 10; $i++) {
            Branch::create([
                'code' => $faker->unique()->randomNumber(3),
                'name' => $faker->city,
            ]);
        }
    }
}
