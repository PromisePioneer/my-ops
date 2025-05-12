<?php

namespace App\Models;

use App\Models\Master\Common\Branch;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Stock extends Model
{
    protected $table = 'stocks';
    protected $fillable = [
        'transaction_id',
        'branch_id',
        'item_id',
        'qty',
        'draft_stock_id',
        'condition',
        'on_hold_qty',
        'initial_balance_inventory_id',
    ];


    public function item(): BelongsTo
    {
        return $this->belongsTo(ItemCollection::class, 'item_id');
    }

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }


    public function itemCatalog(): HasMany
    {
        return $this->hasMany(ItemCatalog::class, 'stock_id');
    }
}
