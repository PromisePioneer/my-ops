<?php

namespace App\Support;

use App\Models\NationalHoliday;
use Carbon\Carbon;
use function App\Helper\formatDate;

class NationalHolidayService
{

    public function getNationalHoliday(Carbon $startDate, Carbon $endDate)
    {
        $nationalHoliday = NationalHoliday::whereBetween('date', [$startDate, $endDate])->orderBy('date')->get();
        return self::formattedData($nationalHoliday);
    }


    private static function formattedData($nationalHoliday)
    {
        return $nationalHoliday->map(function ($item) {
            return [
                'id' => $item->id,
                'date' => formatDate($item->date),
                'description' => $item->name,
            ];
        });
    }
}
