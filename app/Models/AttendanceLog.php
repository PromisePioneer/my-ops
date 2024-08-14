<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_pin',
        'datetime',
        'status',
        'verify_mode',
        'work_code',
        'created_at',
        'updated_at',
    ];
}
