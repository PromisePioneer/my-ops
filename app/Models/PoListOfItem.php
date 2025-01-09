<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PoListOfItem extends Model
{
    protected $table = 'po_list_of_items';
    protected $fillable = [
        'branch_id',
        'invoice_number',
        'po_number',
        'item_id',
        'date',
        'unit_price',
        'qty',
        'shipping_cost',
        'ppn',
        'total_price',
        'supplier_id',
        'travel_letter_receipt',
        'status',
    ];


    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
