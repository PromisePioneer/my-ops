<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockWithdrawalItem extends Model
{
    protected $table = 'stock_withdrawal_items';
    protected $fillable = [
        'stock_withdrawal_id',
        'stock_id',
        'qty',
    ];


    public function stockWithdrawal(): BelongsTo
    {
        return $this->belongsTo(StockWithdrawal::class, 'stock_withdrawal_id');
    }


    public function itemCatalog()
    {
        return $this->belongsTo(ItemCatalog::class, 'item_catalog_id');
    }
}
