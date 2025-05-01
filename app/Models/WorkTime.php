<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Laravel\Scout\Searchable;

class WorkTime extends Model
{
    use HasFactory, Searchable;

    protected $table = 'work_time';

    protected $fillable = [
        'name',
        'clock_in',
        'clock_out',
        'time_to_checkin',
        'end_time_to_checkin',
        'time_to_checkout',
        'end_time_to_checkout',
        'is_default',
    ];


    public function toSearchableArray(): array
    {
        return [
            'name' => $this->name,
        ];
    }


    public function employeeSchedules(): HasOne
    {
        return $this->hasOne(EmployeeSchedule::class, 'work_time_id');
    }
}
