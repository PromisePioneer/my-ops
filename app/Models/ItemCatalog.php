<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ItemCatalog extends Model
{
    protected $table = 'item_catalogs';
    protected $fillable = [
        'transaction_id',
        'stock_id',
        'code',
        'condition',
        'created_by'
    ];


    public function item(): BelongsTo
    {
        return $this->belongsTo(ItemCollection::class, 'item_id');
    }

    public function itemCatalog(): BelongsTo
    {
        return $this->belongsTo(Stock::class, 'stock_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
