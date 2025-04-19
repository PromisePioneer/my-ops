<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttendanceManualHasUser extends Model
{
    protected $table = 'att_manual_has_users';
    protected $fillable = [
        'att_manual_request_id',
        'user_id'
    ];

    public function attendanceManual(): BelongsTo
    {
        return $this->belongsTo(AttendanceManualRequest::class, 'att_manual_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
