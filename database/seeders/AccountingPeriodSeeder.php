<?php

namespace Database\Seeders;

use App\Models\AccountingPeriod;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AccountingPeriodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AccountingPeriod::create([
            'year' => '2020',
        ]);
    }
}
