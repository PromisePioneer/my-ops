<?php

namespace App\Models;

use App\Models\Master\Common\UnitType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Inventory extends Model
{
    use HasFactory;

    protected $table = 'inventory';
    protected $fillable = [
        'sn',
        'name',
        'qty',
        'unit_type_id',
        'price_per_unit',
        'total_price',
    ];


    public function unitType(): BelongsTo
    {
        return $this->belongsTo(UnitType::class, 'unit_type_id');
    }

}
