<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GoodsStock extends Model
{
    protected $table = 'goods_stock';
    protected $fillable = [
        'po_id',
        'warehouse_id',
        'branch_id',
        'item_id',
        'qty',
        'sn',
        'status',
        'created_by'
    ];


    public function po(): BelongsTo
    {
        return $this->belongsTo(GoodsPurchaseOrder::class, 'po_id');
    }


    public function warehouse():BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }


    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Goods::class, 'item_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
