<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PurchaseOrderDetail extends Model
{
    protected $table = 'purchase_order_details';
    protected $fillable = [
        'date',
        'po_id',
        'qty',
        'status',
    ];


    public function po(): BelongsTo
    {
        return $this->belongsTo(GoodsPurchaseOrder::class, 'po_id');
    }
}
