<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        $branch = Branch::where('id', 1)->first();

        User::factory()->create([
            'absent_id' => 999,
            'nip' => 112,
            'join_date' => $faker->date(),
            'name' => 'Super Admin',
            'email' => 'superadmin@mayatama.com',
            'password' => Hash::make('12345678'),
            'branch_id' => null,
            'placement' => 'Pusat',
        ]);
    }
}
