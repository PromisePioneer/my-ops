<?php

namespace Database\Seeders;

use App\Models\PayrollSchedule;
use Illuminate\Database\Seeder;

class PayrollScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PayrollSchedule::create([
            'date' => 28,
        ]);
    }
}
