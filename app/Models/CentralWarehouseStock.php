<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CentralWarehouseStock extends Model
{
    protected $table = 'central_warehouse_stocks';
    protected $fillable = [
        'central_warehouse_item_id',
        'sn',
        'status',
    ];


    public function centralWarehouseItem(): BelongsTo
    {
        return $this->belongsTo(CentralWarehouseItem::class, 'central_warehouse_item_id');
    }


}
