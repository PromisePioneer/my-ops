<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GoodsHasTransaction extends Model
{
    protected $table = 'goods_has_transaction';
    protected $fillable = [
        'goods_transaction_id',
        'item_id'
    ];


    public function goodsTransaction(): BelongsTo
    {
        return $this->belongsTo(GoodsTransaction::class, 'goods_transaction_id');
    }
}
