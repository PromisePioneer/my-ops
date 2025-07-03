<?php

namespace App\Models;

use App\Models\Master\Common\Branch;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\Activitylog\Contracts\Activity;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class NationalHoliday extends Model
{


    use LogsActivity;

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

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }


    public function tapActivity(Activity $activity, $eventName): void
    {
        $properties = $activity->properties->toArray();

        $renameKeys = [

            'name' => 'Nama',
            'date' => 'Tanggal'
        ];


        if ($eventName === 'created') {
            $eventName = 'Membuat Data';
        }


        if ($eventName === 'deleted') {
            $eventName = 'Hapus Data';
        }


        $transformKeys = function ($data) use ($renameKeys, $eventName) {
            return collect($data)
                ->mapWithKeys(function ($value, $key) use ($renameKeys) {
                    $newKey = $renameKeys[$key] ?? $key;
                    return [$newKey => $value];
                });
        };


        $activity->event = $eventName;
        $activity->description = "$eventName Libur Nasional";
        $activity->properties = collect([
            'attributes' => isset($properties['attributes']) ? $transformKeys($properties['attributes']) : null,
            'old' => isset($properties['old']) ? $transformKeys($properties['old']) : null,
        ]);
    }
}
