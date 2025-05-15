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
        'draft_stock_id',
        'item_id',
        'code',
        'condition',
        'created_by',
        'status',
        'initial_balance_inventory_id',
        'asset_id',
    ];

    public function stock(): BelongsTo
    {
        return $this->belongsTo(Stock::class, 'stock_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }


    public function initialInventoryBalance(): BelongsTo
    {
        return $this->belongsTo(InitialInventoryBalance::class, 'initial_balance_inventory_id');
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(ItemCollection::class, 'item_id');
    }
}
