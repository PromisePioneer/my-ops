<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AttendanceManualRequest extends Model
{
    protected $table = 'att_manual_request';
    protected $fillable = [
        'start_date',
        'end_date',
        'work_time_id',
        'status',
        'reason',
        'approved_by'
    ];

    public function workTime(): BelongsTo
    {
        return $this->belongsTo(WorkTime::class, 'work_time_id');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }


    public function user(): HasMany
    {
        return $this->hasMany(AttendanceManualHasUser::class, 'att_manual_request_id');
    }


    public function attachments(): HasMany
    {
        return $this->hasMany(AttendanceManualHasAttachment::class, 'att_manual_request_id');
    }
}
