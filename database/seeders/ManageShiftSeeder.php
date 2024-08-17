<?php

namespace Database\Seeders;

use App\Models\ManageShift;
use Illuminate\Database\Seeder;

class ManageShiftSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ManageShift::create([
            'id' => 1,
            'name' => 'Kantor',
            'clock_in' => '08:00',
            'clock_out' => '17:00',
            'time_to_checkin' => '06:00',
            'end_time_to_checkin' => '10:00',
            'time_to_checkout' => '17:00',
            'end_time_to_checkout' => '23:59'
        ]);
    }
}
