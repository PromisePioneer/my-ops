<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CentralWarehouseStock extends Model
{
    protected $table = 'central_warehouse_stocks';
    protected $fillable = [
        'po_id',
        'warehouse_id',
        'item_id',
        'qty',
        'sn',
        'status',
    ];


    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }


}
