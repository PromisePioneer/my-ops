<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttendanceManualHasAttachment extends Model
{
    protected $table = 'att_manual_has_attachments';
    protected $fillable = [
        'att_manual_request_id',
        'attachment'
    ];

    public function attendanceManual(): BelongsTo
    {
        return $this->belongsTo(AttendanceManualRequest::class, 'att_manual_request_id');
    }
}
