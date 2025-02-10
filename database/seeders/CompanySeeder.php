<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Company::create([
            'code' => 001,
            'name' => 'Mayatama Solusindo',
        ]);

        Company::create([
            'code' => 002,
            'name' => 'CV. Prestasi Sukses Gemilang',
        ]);


    }
}
