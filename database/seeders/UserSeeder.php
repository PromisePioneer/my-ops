<?php

namespace Database\Seeders;

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
        User::factory()->create([
            'nip' => 123,
            'name' => 'Kepala Cabang',
            'email' => 'kacab@mayatama.com',
            'password' => Hash::make('12345678'),
            'branch_id' => 1,
        ]);

        User::factory()->create([
            'nip' => 456,
            'name' => 'Accountant',
            'department_id' => 2,
            'email' => 'akuntan@mayatama.com',
            'password' => Hash::make('12345678'),
            'branch_id' => 1,
        ]);

        User::factory()->create([
            'nip' => 789,
            'name' => 'Manager Keuangan',
            'email' => 'manager-keuangan@mayatama.com',
            'password' => Hash::make('12345678'),
            'branch_id' => 1,
        ]);

        User::factory()->create([
            'nip' => 111,
            'name' => 'Direktur',
            'email' => 'direktur@mayatama.com',
            'password' => Hash::make('12345678'),
            'branch_id' => 2,
        ]);

        User::factory()->create([
            'nip' => 112,
            'name' => 'Super Admin',
            'email' => 'superadmin@mayatama.com',
            'password' => Hash::make('12345678'),
            'branch_id' => 1,
        ]);

        User::factory()->create([
            'nip' => 1223,
            'name' => 'Stocker',
            'email' => 'stocker@mayatama.com',
            'password' => Hash::make('12345678'),
            'department_id' => 2,
            'branch_id' => 1,
        ]);

        for ($i = 0; $i < 10; $i++) {
            User::factory()->create([
                'nip' => $faker->unique()->randomNumber(),
                'name' => 'KCA',
                'email' => $faker->unique()->email,
                'password' => Hash::make('12345678'),
                'branch_id' => 1,
            ]);

            User::factory()->create([
                'nip' => $faker->unique()->randomNumber(),
                'name' => 'WKCA',
                'email' => $faker->unique()->email,
                'password' => Hash::make('12345678'),
                'branch_id' => 1,
            ]);
        }

        User::factory()->create([
            'nip' => 115,
            'name' => 'NOC',
            'email' => 'noc@mayatama.com',
            'password' => Hash::make('12345678'),
            'department_id' => 2,
            'branch_id' => 1,
        ]);

        for ($i = 0; $i < 10; $i++) {
            User::factory()->create([
                'nip' => $faker->unique()->randomNumber(),
                'name' => 'Technician',
                'department_id' => 2,
                'email' => $faker->unique()->email,
                'password' => Hash::make('12345678'),
                'branch_id' => 1,
            ]);
        }

        for ($i = 0; $i < 10; $i++) {
            User::factory()->create([
                'nip' => $faker->unique()->randomNumber(),
                'department_id' => 1,
                'name' => 'Customer Service',
                'email' => $faker->unique()->email,
                'password' => Hash::make('12345678'),
                'branch_id' => 1,
            ]);
        }
    }
}
