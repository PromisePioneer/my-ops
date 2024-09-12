<?php

namespace Database\Seeders;

use App\Models\SalaryCalculationMethod;
use Illuminate\Database\Seeder;

class SalaryCalculationMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SalaryCalculationMethod::create([
            'name' => 'Gross',
        ]);

        SalaryCalculationMethod::create([
            'name' => 'Gross Up',
        ]);

        SalaryCalculationMethod::create([
            'name' => 'Netto',
        ]);
    }
}
