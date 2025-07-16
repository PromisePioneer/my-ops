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
            'name' => 'Pagi',
            'clock_in' => '08:00',
            'clock_out' => '17:00',
            'time_to_checkin' => '07:00',
            'end_time_to_checkin' => '10:00',
            'time_to_checkout' => '17:00',
            'end_time_to_checkout' => '23:59',
            'is_default' => true
        ]);

        WorkTime::create([
            'name' => 'Lapangan',
            'clock_in' => '09:00',
            'clock_out' => '18:00',
            'time_to_checkin' => '07:00',
            'end_time_to_checkin' => '11:00',
            'time_to_checkout' => '18:00',
            'end_time_to_checkout' => '23:59',
        ]);


        WorkTime::create([
            'name' => 'Malam',
            'clock_in' => '00:00',
            'clock_out' => '09:00',
            'time_to_checkin' => '23:00',
            'end_time_to_checkin' => '02:00',
            'time_to_checkout' => '09:00',
            'end_time_to_checkout' => '12:00',
        ]);

        WorkTime::create([
            'name' => 'Sore',
            'clock_in' => '16:00',
            'clock_out' => '01:00',
            'time_to_checkin' => '15:00',
            'end_time_to_checkin' => '18:00',
            'time_to_checkout' => '01:00',
            'end_time_to_checkout' => '05:00',
        ]);

        WorkTime::create([
            'name' => 'KU Malam',
            'clock_in' => '18:00',
            'clock_out' => '03:00',
            'time_to_checkin' => '17:00',
            'end_time_to_checkin' => '20:00',
            'time_to_checkout' => '03:00',
            'end_time_to_checkout' => '06:00',
        ]);
    }
}
