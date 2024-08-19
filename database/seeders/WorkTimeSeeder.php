<?php

namespace Database\Seeders;

use App\Models\WorkTime;
use Illuminate\Database\Seeder;

class WorkTimeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        WorkTime::create([
            'id' => 1,
            'name' => 'Default',
            'clock_in' => '08:00',
            'clock_out' => '17:00',
            'time_to_checkin' => '07:00',
            'end_time_to_checkin' => '10:00',
            'time_to_checkout' => '17:00',
            'end_time_to_checkout' => '23:59'
        ]);
    }
}
