<?php

namespace Database\Seeders;

use App\Models\CompanyProfile;
use Illuminate\Database\Seeder;

class CompanyProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CompanyProfile::create([
            'name' => "PT Mayatama Solusindo",
            'address' => "Jl. Sultan Hasanuddin No. 8 A, Kel. Rimba Sekampung",
            'npwp' => '03.255.576.5-212.000',
            'bank' => 'BANK MANDIRI',
            'bank_account_number' => '172- 00- 0206532- 6 (IDR)',
            'bank_account_name' => 'PT MAYATAMA SOLUSINDO',
        ]);
    }
}
