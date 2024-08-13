<?php

namespace Database\Seeders;

use App\Models\Contact;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class ContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('id_ID');

        for ($i = 1; $i <= 1000; $i++) {
            Contact::create([
                'branch_id' => null,
                'full_name' => $faker->name,
                'company_name' => $faker->company,
                'email' => $faker->email,
                'phone_number' => $faker->phoneNumber,
                'identity_type' => 'ktp',
                'identity_number' => $faker->randomNumber(),
                'fax' => $faker->phoneNumber,
                'npwp' => $faker->unique()->randomNumber(),
                'complete_address' => $faker->address,
                'other_info' => $faker->text,
            ]);
        }
    }
}
