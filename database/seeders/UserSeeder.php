<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

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
            'email' => 'superadmin@mayatama.net',
            'password' => Hash::make('12345678'),
            'branch_id' => 1,
            'company_id' => 1,
            'placement' => 'Pusat',
        ]);


        $director = User::factory()->create([
            'absent_id' => 1,
            'nip' => 1,
            'join_date' => $faker->date(),
            'name' => 'Director',
            'email' => 'director@mayatama.net',
            'password' => Hash::make('12345678'),
            'branch_id' => null,
            'company_id' => 1,
            'placement' => 'Pusat',
        ]);


        $operationalManager = User::factory()->create([
            'absent_id' => 2,
            'nip' => 2,
            'join_date' => $faker->date(),
            'name' => 'Operational Manager',
            'email' => 'operationalmanager@mayatama.net',
            'password' => Hash::make('12345678'),
            'branch_id' => null,
            'company_id' => 1,
            'placement' => 'Pusat',
        ]);


        $branchManager = User::factory()->create([
            'absent_id' => 3,
            'nip' => 3,
            'join_date' => $faker->date(),
            'name' => 'Branch Manager',
            'email' => 'branchmanager@mayatama.net',
            'password' => Hash::make('12345678'),
            'branch_id' => 3,
            'company_id' => 1,
            'placement' => 'Pusat',
        ]);


        $financeManager = User::factory()->create([
            'absent_id' => 4,
            'nip' => 4,
            'join_date' => $faker->date(),
            'name' => 'FA & Tax Manager',
            'email' => 'fa&taxmanager@mayatama.net',
            'password' => Hash::make('12345678'),
            'branch_id' => null,
            'company_id' => 1,
            'placement' => 'Pusat',
        ]);


        $generalManager = User::factory()->create([
            'absent_id' => 5,
            'nip' => 5,
            'join_date' => $faker->date(),
            'name' => 'General Manager',
            'email' => 'generalmanager@mayatama.net',
            'password' => Hash::make('12345678'),
            'branch_id' => null,
            'company_id' => 1,
            'placement' => 'Pusat',
        ]);


        for ($i = 0; $i < 10; $i++) {
            $kca = User::factory()->create([
                'absent_id' => fake()->unique()->randomDigit(),
                'join_date' => $faker->date(),
                'name' => fake()->unique()->name,
                'email' => fake()->unique()->email . '@mayatama.net',
                'password' => Hash::make('12345678'),
                'branch_id' => 3,
                'company_id' => 1,
                'placement' => 'Cabang',
            ]);

            $kca->assignRole('Head Engineer');
        }


        $superAdminRole = Role::create(['name' => 'Super Admin']);
        $superAdmin = User::where('name', 'Super Admin')->first();
        $superAdmin->assignRole($superAdminRole);

    }
}
