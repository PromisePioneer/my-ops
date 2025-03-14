<?php

namespace App\Models;

use App\Models\Master\Common\Branch;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GoodsPurchaseOrder extends Model
{
    protected $table = 'goods_purchase_order';
    protected $fillable = [
        'branch_id',
        'warehouse_id',
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
        'status_send',
        'status_received',
        'send_by',
        'send_by',
        'received_by',
    ];


    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Goods::class, 'item_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }


    public function sendBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'send_by');
    }


    public function receivedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'received_by');
    }
}
