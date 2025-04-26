<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PickingStockHasItem extends Model
{
    protected $table = 'picking_stock_has_items';
    protected $fillable = [
        'picking_stock_id',
        'stock_id',
        'qty'
    ];


    public function pickingStock(): BelongsTo
    {
        return $this->belongsTo(PickingStock::class, 'picking_stock_id');
    }

    public function stock(): BelongsTo
    {
        return $this->belongsTo(Stock::class, 'stock_id');
    }
}
