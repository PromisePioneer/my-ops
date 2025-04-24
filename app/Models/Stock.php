<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Stock extends Model
{
    protected $table = 'stocks';
    protected $fillable = [
        'transaction_id',
        'draft_stock_id',
        'branch_id',
        'item_id',
        'qty',
    ];


    public function item(): BelongsTo
    {
        return $this->belongsTo(ItemCollection::class, 'item_id');
    }

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }
}
