<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OfferingLetterProduct extends Model
{
    use HasFactory;
    protected $table = 'offering_letter_product_services';
    protected $fillable = [
        'offering_letter_id',
        'service_category_id',
        'qty',
        'unit_price',
        'total_price',
    ];

    public function offeringLetter(): BelongsTo
    {
        return $this->belongsTo(OfferingLetter::class, 'offering_letter_id');
    }

    public function serviceCategory(): BelongsTo
    {
        return $this->belongsTo(ServiceCategory::class, 'service_category_id');
    }


    //eloquent
    public function getOfferingLetterProductServiceAttribute(int $offeringLetterId)
    {
        return self::with('offeringLetter', 'serviceCategory')
            ->where('offering_letter_id', $offeringLetterId)
            ->get();
    }
}
