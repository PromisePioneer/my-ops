<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttendanceCorrection extends Model
{
    protected $table = 'manual_attendance_requests';
    protected $fillable = [
        'user_id',
        'date',
        'status',
        'reason',
        'confirmation_reason',
        'type',
        'attachment',
        'approved_by'
    ];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
