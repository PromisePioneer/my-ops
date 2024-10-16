<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttendancesSummary extends Model
{
    use HasFactory;

    protected $table = 'attendances_summary';
    protected $fillable = [
        'employee_id',
        'date',
        'clock_in',
        'clock_out',
        'work_time_id',
    ];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'employee_id', 'absent_id');
    }

}
