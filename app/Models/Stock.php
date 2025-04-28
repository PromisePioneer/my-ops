<?php

namespace App\Models;

use App\Models\Master\Common\Branch;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Stock extends Model
{
    protected $table = 'stocks';
    protected $fillable = [
        'transaction_id',
        'item_catalog_id',
        'branch_id',
        'item_id',
        'qty',
        'condition'
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


    public function itemCatalog(): BelongsTo
    {
        return $this->belongsTo(ItemCatalog::class, 'item_catalog_id');
    }
}
