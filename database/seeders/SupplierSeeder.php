<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Supplier::create([
            'name' => 'PT. Virtus Technology Indonesia',
            'code' => "ASD",
            'address' => 'Nice',
            'city' => 'Jakarta',
            'province' => 'Jawa',
            'country' => 'Indonesia',
            'postal_code' => '02123',
            'fax' => '02123',
            'email' => 'admins@asdasd.com',
            'phone_number' => '02123',
            'bank_account_number' => '02123',
            'bank_account_name' => 'Javanicus',
            'bank_name' => 'test',
            'npwp' => '123213213',
            'description' => 'asdasd',
            'tax_type' => 'PKP',
        ]);
    }
}
