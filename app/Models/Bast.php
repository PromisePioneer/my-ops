<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Bast extends Model
{
    protected $table = 'bast';

    protected $fillable = [
        'baa_id',
        'date',
        'bast_number',
        'invoice_address',
        'status'
    ];


    public function baa(): BelongsTo
    {
        return $this->belongsTo(Baa::class, 'baa_id');
    }
}
