<?php

namespace App\Service;

use App\Models\CutOffPayrollSetting;
use Carbon\Carbon;

class FinancialClosePeriodService
{
    public function startDate(): Carbon
    {
        $year = Carbon::now()->year;
        $month = Carbon::now()->subMonth(1)->month;
        $date = CutOffPayrollSetting::first()->attendance_period_start;
        return Carbon::parse($year.'-'.$month.'-'.$date);
    }


    public function endDate(): Carbon
    {
        $year = Carbon::now()->year;
        $month = Carbon::now()->month;
        $date = CutOffPayrollSetting::first()->attendance_period_end;

        return Carbon::parse($year.'-'.$month.'-'.$date);
    }
}