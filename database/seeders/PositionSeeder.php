<?php

namespace Database\Seeders;

use App\Models\Position;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        $roles = Role::all();

        foreach ($roles as $role) {
            Position::create([
                'code' => $faker->unique()->randomNumber(6),
                'name' => $role->name,
            ]);
        }
    }
}
