<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttendanceJobProgress extends Model
{
    protected $table = 'attendance_job_progress';
    protected $fillable = [
        'device_id',
        'start_date',
        'end_date',
        'status',
    ];


    public function device(): BelongsTo
    {
        return $this->belongsTo(FpDevice::class, 'device_id');
    }
}
