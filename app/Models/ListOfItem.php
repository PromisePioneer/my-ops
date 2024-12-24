<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ListOfItem extends Model
{
    protected $table = 'list_of_items';
    protected $fillable = [
        'sn',
        'name',
        'date',
        'unit_price',
        'qty',
        'shipping_cost',
        'ppn',
        'total_price',
        'supplier_id',
        'supplier_id',
        'travel_letter_receipt',
    ];


    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }
}
