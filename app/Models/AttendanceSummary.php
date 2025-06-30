<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttendanceSummary extends Model
{

    protected $table = 'attendances_summary';
    protected $fillable = [
        'employee_id',
        'date',
        'clock_in',
        'clock_out',
        'work_time_id',
        'type'
    ];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'employee_id', 'absent_id');
    }

    public function workTime(): BelongsTo
    {
        return $this->belongsTo(WorkTime::class, 'work_time_id');
    }
}
