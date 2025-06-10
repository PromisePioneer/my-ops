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
        'code',
        'qty',
        'status',
        'qty_in_meter',
    ];


    public function stock(): BelongsTo
    {
        return $this->belongsTo(Stock::class, 'stock_id');
    }


    public function stockWithdrawal(): BelongsTo
    {
        return $this->belongsTo(StockWithdrawal::class, 'stock_withdrawal_id');
    }
}
