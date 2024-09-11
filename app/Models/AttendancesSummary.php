<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendancesSummary extends Model
{
    use HasFactory;

    protected $table = 'attendances_summary';
    protected $fillable = [
        'user_id',
        'date',
        'clock_in',
        'clock_out',
    ];
}
