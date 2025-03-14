<?php

namespace App\Models;

use App\Models\Master\Common\SKL;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OfferingLetterServiceDescription extends Model
{
    protected $table = 'offering_letter_skl';
    protected $fillable = [
        'offering_letter_id',
        'skl_id',
    ];


    public function skl(): BelongsTo
    {
        return $this->belongsTo(SKL::class, 'skl_id');
    }


    public function offeringLetter(): BelongsTo
    {
        return $this->belongsTo(OfferingLetter::class, 'offering_letter_id');
    }
}
