<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeSchedule extends Model
{
    protected $table = 'employee_schedules';
    protected $fillable = [
        'work_time_id',
        'employee_id',
        'is_cross_midnight_shift',
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
}
