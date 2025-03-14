<?php

namespace App\Models;

use App\Models\Master\Common\UnitType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BoqCommodity extends Model
{
    use HasFactory;

    protected $table = 'boq_commodities';
    protected $fillable = [
        'boq_id',
        'item_id',
        'merk',
        'qty',
        'unit_type_id',
        'unit_price',
        'total_price',
        'used_estimation',
        'description',
    ];


    public function boq(): BelongsTo
    {
        return $this->belongsTo(Boq::class, 'boq_id');
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Goods::class, 'item_id');
    }


    public function unitType(): BelongsTo
    {
        return $this->belongsTo(UnitType::class, 'unit_type_id');
    }
}
