<?php

namespace Database\Seeders;

use App\Models\CutOffPayrollSetting;
use Illuminate\Database\Seeder;

class CutOffPayrollSettingSeeder extends Seeder
{
    public function run(): void
    {
        CutOffPayrollSetting::create([
            'attendance_period_start' => 28,
            'attendance_period_end' => 27,
            'emp_payroll_period_start' => 28,
            'emp_payroll_period_end' => 27,
        ]);
    }
}
