<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReturnItemFromPo extends Model
{
    protected $table = 'return_items_from_po';
    protected $fillable = [
        'po_id',
        'item_id',
        'qty',
        'reason'
    ];

    public function item(): BelongsTo
    {
        return $this->belongsTo(ItemCollection::class, 'item_id');
    }
}
