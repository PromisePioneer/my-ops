<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FPDeviceCommand extends Model
{
    protected $table = 'fp_device_commands';
    protected $fillable = [
        'device_id',
        'user_id',
        'start_date',
        'end_date',
        'type',
        'command',
        'status'
    ];


    public function device(): BelongsTo
    {
        return $this->belongsTo(FpDevice::class, 'device_id');
    }
}
