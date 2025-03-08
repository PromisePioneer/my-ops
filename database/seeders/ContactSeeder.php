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
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        for ($i = 1; $i <= 50; $i++) {
            Contact::create([
                'pic_name' => $faker->name,
                'company_name' => $faker->unique()->company,
                'company_code' => $faker->unique()->randomNumber(3),
                'phone_number' => $faker->phoneNumber(),
                'pic_position' => 'Position'
            ]);
        }
    }
}
