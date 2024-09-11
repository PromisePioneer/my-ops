<?php

namespace Database\Seeders;

use App\Models\CutOffPayrollSetting;
use Illuminate\Database\Seeder;

class CutOffPayrollSettingSeeder extends Seeder
{
    public function run(): void
    {
        CutOffPayrollSetting::create([
            'attendance_period_start' => 1,
            'attendance_period_end' => 31,
            'payroll_period_start' => 1,
            'payroll_period_end' => 31,
        ]);
    }
}
