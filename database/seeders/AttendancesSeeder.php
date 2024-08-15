<?php

namespace Database\Seeders;

use App\Models\Attendances;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AttendancesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Attendances::create([
            'sn' => 'AEWD233960062',
            'table' => 'test',
            'stamp' => Carbon::now(),
            'employee_id' => 17,
            'timestamp' => Carbon::now(),
            'status1' => 0,
        ]);
    }
}
