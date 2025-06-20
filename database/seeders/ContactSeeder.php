<?php

namespace Database\Seeders;

use App\Models\Master\Common\Contact;
use App\Models\Supplier;
use Illuminate\Database\Seeder;

class ContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        //pkp
        Contact::create([
            'name' => 'PT MORA TELEMATIKA INDONESIA TBK',
            'code' => "MTI",
            'address' => 'Grha 9, Jalan Penataran No. 9 Kel. Pegangsaan, Kec. MentengJakarta Pusat 10320, Indonesia',
            'city' => 'Jakarta',
            'province' => 'DKI JAKARTA',
            'country' => 'Indonesia',
            'postal_code' => '10320',
            'fax' => '+6221 314 2882',
            'email' => 'info@moratelindo.co.id',
            'phone_number' => '+6221 3199 8600',
            'bank_account_number' => '-',
            'bank_account_name' => '-',
            'bank_name' => '-',
            'npwp' => '-',
            'description' => '-',
            'type' => 'Supplier',
            'tax_type' => 'PKP',
        ]);


        Contact::create([
            'name' => fake()->company(),
            'code' => "TES",
            'address' => fake()->address(),
            'city' => fake()->city(),
            'province' => fake()->city(),
            'country' => fake()->country(),
            'postal_code' => fake()->postcode(),
            'fax' => fake()->postcode,
            'email' => fake()->companyEmail(),
            'phone_number' => fake()->phoneNumber(),
            'bank_account_number' => fake()->randomNumber(),
            'bank_account_name' => fake()->company(),
            'bank_name' => '-',
            'npwp' => '-',
            'description' => '-',
            'type' => 'Supplier',
            'tax_type' => 'NON PKP',
        ]);


    }
}
