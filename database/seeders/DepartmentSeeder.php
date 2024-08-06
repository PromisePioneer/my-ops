<?php

namespace Database\Seeders;

use App\Models\Department;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        Department::create([
            'code' => $faker->unique()->randomNumber(6),
            'name' => 'Keuangan',
        ]);

        Department::create([
            'code' => $faker->unique()->randomNumber(6),
            'name' => 'Operasional',
        ]);
    }
}
