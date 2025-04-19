<?php

namespace App\Models;

use App\Models\Master\Common\UnitType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PurchaseOrderItem extends Model
{
    protected $table = 'po_items';
    protected $fillable = [
        'po_id',
        'item',
        'unit_type_id',
        'qty',
        'price'
    ];


    public function po(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrder::class, 'po_id');
    }

    public function unitType(): BelongsTo
    {
        return $this->belongsTo(UnitType::class, 'unit_type_id');
    }
}
