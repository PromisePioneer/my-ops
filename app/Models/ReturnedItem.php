<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReturnedItem extends Model
{
    protected $table = 'returned_items';
    protected $fillable = [
        'stock_withdrawal_item_id',
        'status',
        'remaining_qty',
        'item_condition',
        'broken_qty'
    ];


    public function stockWithdrawalItem(): BelongsTo
    {
        return $this->belongsTo(StockWithdrawalItem::class, 'stock_withdrawal_item_id');
    }
}
