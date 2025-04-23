<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ListOfItem extends Model
{
    protected $table = 'list_of_items';
    protected $fillable = [
        'transaction_id',
        'item_id',
        'code',
        'condition'
    ];


    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(ItemCollection::class, 'item_id');
    }
}
