<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OfferingLetterServiceDescription extends Model
{
    protected $table = 'offering_letter_service_descriptions';
    protected $fillable = [
        'offering_letter_id',
        'skl_id',
    ];


    public function skl(): BelongsTo
    {
        return $this->belongsTo(OfferingLetterSKL::class, 'skl_id');
    }


    public function offeringLetter(): BelongsTo
    {
        return $this->belongsTo(OfferingLetter::class, 'offering_letter_id');
    }
}
