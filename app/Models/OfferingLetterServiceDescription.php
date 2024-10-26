<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OfferingLetterServiceDescription extends Model
{
    protected $table = 'offering_letter_service_descriptions';
    protected $fillable = [
        'offering_letter_id',
        'text',
    ];


    public function offeringLetter(): BelongsTo
    {
        return $this->belongsTo(OfferingLetter::class, 'offering_letter_id');
    }
}
