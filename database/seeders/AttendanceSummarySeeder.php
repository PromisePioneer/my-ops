<?php

namespace Database\Seeders;

use App\Models\AttendanceSummary;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AttendanceSummarySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AttendanceSummary::create([
            'date' => Carbon::now()->format('Y-m-d'),
            'employee_id' => 120,
            'clock_in' => Carbon::parse('2025-05-01 08:10:00'),
        ]);

    }
}
