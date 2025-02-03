<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockHasSN extends Model
{
    protected $table = 'stock_has_sn';

    protected $fillable = [
        'goods_stock_id',
        'serial_number',
    ];

    public function goodsStock(): BelongsTo
    {
        return $this->belongsTo(GoodsStock::class, 'goods_stock_id');
    }
}
