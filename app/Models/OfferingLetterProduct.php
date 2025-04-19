<?php

namespace App\Models;

use App\Models\Master\Common\ServiceCategory;
use App\Models\Master\Common\UnitType;
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
        'capacity',
        'unit_type_id',
        'price',
    ];

    public function unitType(): BelongsTo
    {
        return $this->belongsTo(UnitType::class, 'unit_type_id');
    }

    public function offeringLetter(): BelongsTo
    {
        return $this->belongsTo(OfferingLetter::class, 'offering_letter_id');
    }

    public function serviceCategory(): BelongsTo
    {
        return $this->belongsTo(ServiceCategory::class, 'service_category_id');
    }

}
