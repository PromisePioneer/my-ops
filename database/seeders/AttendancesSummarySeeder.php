<?php

namespace Database\Seeders;

use App\Models\AttendancesSummary;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AttendancesSummarySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AttendancesSummary::create([
            'date' => Carbon::parse('2024-10-03'),
            'employee_id' => 999,
            'clock_in' => '08:35:00',
            'clock_out' => Carbon::now()->format('H:i'),
        ]);



        AttendancesSummary::create([
            'date' => Carbon::parse('2024-10-04'),
            'employee_id' => 999,
            'clock_in' => '08:35:00',
            'clock_out' => Carbon::now()->format('H:i'),
        ]);
    }
}
