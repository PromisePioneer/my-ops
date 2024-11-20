<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class BAA extends Model
{
    protected $table = 'baa';
    protected $fillable = [
        'fab_id',
        'baa_number',
        'date',
        'work_location',
        'status'
    ];


    public function fab(): BelongsTo
    {
        return $this->belongsTo(Fab::class, 'fab_id');
    }


    public function spk(): HasOne
    {
        return $this->hasOne(SPK::class, 'baa_id');
    }

}
