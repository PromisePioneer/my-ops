<?php

namespace Database\Seeders;

use App\Models\BPJSKet;
use Illuminate\Database\Seeder;

class BPJSKetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BPJSKet::create([
            'name' => 'JHT',
            'company_rate' => 2,
            'employee_rate' => 3.7,
        ]);


        BPJSKet::create([
            'name' => 'JKK',
            'company_rate' => 0.54,
            'employee_rate' => null,
        ]);

        BPJSKet::create([
            'name' => 'JKM',
            'company_rate' => 0.3,
            'employee_rate' => null,
        ]);

        BPJSKet::create([
            'name' => 'JP',
            'company_rate' => 2,
            'employee_rate' => 1,
        ]);
    }
}
