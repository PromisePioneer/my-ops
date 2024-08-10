<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $offering_letter_id
 * @property int $service_category_id
 * @property int $qty
 * @property float $unit_price
 * @property float $total_price
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $offering_letter_product_service
 * @property-read \App\Models\OfferingLetter $offeringLetter
 * @property-read \App\Models\ServiceCategory $serviceCategory
 *
 * @method static \Illuminate\Database\Eloquent\Builder|OfferingLetterProduct newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OfferingLetterProduct newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OfferingLetterProduct query()
 * @method static \Illuminate\Database\Eloquent\Builder|OfferingLetterProduct whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfferingLetterProduct whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfferingLetterProduct whereOfferingLetterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfferingLetterProduct whereQty($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfferingLetterProduct whereServiceCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfferingLetterProduct whereTotalPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfferingLetterProduct whereUnitPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfferingLetterProduct whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
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
