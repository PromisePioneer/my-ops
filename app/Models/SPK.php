<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SPK extends Model
{
    protected $table = 'spk';
    protected $fillable = [
        'baa_id',
        'spk_number',
        'name',
        'date',
        'start_date',
        'end_date',
        'from',
        'to'
    ];

    public function baa(): BelongsTo
    {
        return $this->belongsTo(BAA::class, 'baa_id');
    }

    public function spkFrom(): BelongsTo
    {
        return $this->belongsTo(User::class, 'from');
    }

    public function spkTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'to');
    }
}
