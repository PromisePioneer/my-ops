<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class Attendances extends Model
{
    use HasFactory;

    protected $table = 'attendances';
    protected $fillable = [
        'sn',
        'table',
        'stamp',
        'employee_id',
        'timestamp',
        'status1',
    ];


    public function getAttendanceWithPagination(int $perPage)
    {
        $attendances = self::orderBy('timestamp', 'DESC')->paginate($perPage);
        self::formattedData($attendances);
        return $attendances;
    }


    private static function formattedData(LengthAwarePaginator $data)
    {
        $formattedData = $data->getCollection()->map(function ($item) {
            return [
                'id' => $item->id,
                'sn' => $item->sn,
                'employee_id' => $item->employee_id,
                'timestamp' => Carbon::parse($item->timestamp)->locale('id')->settings(['formatFunction' => 'translatedFormat'])->format('l, j F Y H:i'),
                'status1' => $item->status1,
                'status2' => $item->status2,
                'status3' => $item->status3,
                'status4' => $item->status4,
                'status5' => $item->status5,
            ];
        });

        $data->setCollection($formattedData);

        return $data;
    }

}
