<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GoodsStockDetail extends Model
{
    protected $table = 'goods_stock_detail';
    protected $fillable = [
        'po_id',
        'stock_id',
        'item_id',
        'item_code',
    ];


    public function po(): BelongsTo
    {
        return $this->belongsTo(GoodsPurchaseOrder::class, 'po_id');
    }


    public function stock(): BelongsTo
    {
        return $this->belongsTo(GoodsPurchaseOrder::class, 'stock_id');
    }

}
