<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeviceLog extends Model
{
    use HasFactory;

    protected $table = 'device_logs';
    protected $fillable = [
        'data',
        'date',
        'serial_number',
        'option',
        'url',
    ];
}
