<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $offering_letter_id
 * @property int $service_category_id
 * @property int $qty
 * @property float $unit_price
 * @property float $total_price
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read mixed $offering_letter_product_service
 * @property-read OfferingLetter $offeringLetter
 * @property-read ServiceCategory $serviceCategory
 *
 * @method static Builder|OfferingLetterProduct newModelQuery()
 * @method static Builder|OfferingLetterProduct newQuery()
 * @method static Builder|OfferingLetterProduct query()
 * @method static Builder|OfferingLetterProduct whereCreatedAt($value)
 * @method static Builder|OfferingLetterProduct whereId($value)
 * @method static Builder|OfferingLetterProduct whereOfferingLetterId($value)
 * @method static Builder|OfferingLetterProduct whereQty($value)
 * @method static Builder|OfferingLetterProduct whereServiceCategoryId($value)
 * @method static Builder|OfferingLetterProduct whereTotalPrice($value)
 * @method static Builder|OfferingLetterProduct whereUnitPrice($value)
 * @method static Builder|OfferingLetterProduct whereUpdatedAt($value)
 *
 * @mixin Eloquent
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

}
