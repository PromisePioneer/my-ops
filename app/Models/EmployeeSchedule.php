<?php

namespace App\Models;

use App\Models\Master\Common\Branch;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Contracts\Activity;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class EmployeeSchedule extends Model
{

    use LogsActivity;

    protected $table = 'employee_schedules';
    protected $fillable = [
        'work_time_id',
        'employee_id',
        'start_date',
        'end_date',
        'status'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'employee_id', 'absent_id');
    }

    public function workTime(): BelongsTo
    {
        return $this->belongsTo(WorkTime::class, 'work_time_id', 'id');
    }


    public function tapActivity(Activity $activity, $eventName): void
    {
        $properties = $activity->properties->toArray();

        $renameKeys = [
            'work_time_id' => 'Jam Kerja',
            'employee_id' => 'ID Absensi',
            'Nama' => 'Nama Absensi',
            'start_date' => 'Tanggal Mulai',
            'end_date' => 'Tanggal Akhir',
            'status' => 'Status',
        ];


        if ($eventName === 'updated') {
            $eventName = 'Mengubah Data';
        }


        if ($eventName === 'created') {
            $eventName = 'Membuat Data';
        }


        if ($eventName === 'deleted') {
            $eventName = 'Hapus Data';
        }


        $relationResolvers = [
            'Jam Kerja' => fn($id) => WorkTime::find($id)?->name,
            'Nama' => fn($id) => User::where('absent_id', $id)?->name,
        ];

        $transformKeys = function ($data) use ($renameKeys, $relationResolvers, $eventName) {
            return collect($data)
                ->mapWithKeys(function ($value, $key) use ($renameKeys, $relationResolvers) {
                    $newKey = $renameKeys[$key] ?? $key;
                    if (array_key_exists($key, $relationResolvers)) {
                        $value = $relationResolvers[$key]($value);
                    }

                    return [$newKey => $value];
                });
        };


        $activity->event = $eventName;
        $activity->description = "$eventName Jadwal Libur";
        $activity->properties = collect([
            'attributes' => isset($properties['attributes']) ? $transformKeys($properties['attributes']) : null,
            'old' => isset($properties['old']) ? $transformKeys($properties['old']) : null,
        ]);
    }


    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->dontSubmitEmptyLogs();
    }
}
