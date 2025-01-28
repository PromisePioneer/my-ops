<?php

namespace App\Service\HelperService;

use App\Models\CutOffPayrollSetting;
use Carbon\Carbon;

class FinancialClosePeriodService
{
    public function startDate(): Carbon
    {
        $year =  Carbon::now()->year;
        $month = Carbon::now()->subMonthNoOverflow()->month;


        $date = CutOffPayrollSetting::first()->attendance_period_start;
        $dateNow = (int)Carbon::now()->format('d');

        if ($dateNow >= $date) {
            $month = Carbon::now()->month;
            return Carbon::parse($year . '-' . $month . '-' . $date);
        }

        return Carbon::parse($year . '-' . $month . '-' . $date);
    }


    public function endDate(): Carbon
    {
        $year = Carbon::now()->month == 12 ? Carbon::now()->addYear()->year : Carbon::now()->year;
        $month = Carbon::now()->month;
        $date = CutOffPayrollSetting::first()->attendance_period_end;
        $startDate = (int)$this->startDate()->format('m');

        $monthNow = (int)Carbon::now()->format('m');


        if ($monthNow === $startDate) {
            $month = Carbon::now()->addMonthNoOverflow()->month;

            return Carbon::parse($year . '-' . $month . '-' . $date);
        }

        return Carbon::parse($year . '-' . $month . '-' . $date);
    }
}
