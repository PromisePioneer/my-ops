<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class NationalHoliday extends Model
{
    use HasFactory;

    protected $table = 'national_holidays';
    protected $fillable = [
        'name',
        'date',
    ];


    public function getData(): LengthAwarePaginator
    {
        $nationalHoliday = self::orderBy('date', 'asc')->paginate(10);
        self::formattedData($nationalHoliday);

        return $nationalHoliday;
    }


    private static function formattedData(LengthAwarePaginator $nationalHoliday): void
    {
        $data = $nationalHoliday->getCollection()->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'date' => Carbon::parse($item->date)->translatedFormat('d F Y'),
            ];
        });

        $nationalHoliday->setCollection($data);
    }
}
