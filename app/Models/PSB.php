<?php

namespace App\Models;

use App\Models\Master\Common\Branch;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PSB extends Model
{
    protected $table = 'psb';
    protected $fillable = [
        'date',
        'registration_date',
        'active_date',
        'customer_name',
        'phone_number',
        'address',
        'area_id',
        'pic',
    ];


    public function broadbandPacket(): BelongsTo
    {
        return $this->belongsTo(BroadbandPacket::class, 'packet_id');
    }


    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class, 'area_id');
    }
}
