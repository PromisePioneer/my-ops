<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GoodsTransaction extends Model
{
    protected $table = 'goods_transaction';
    protected $fillable = [
        'date',
        'po_id',
        'item_id',
        'from_warehouse_id',
        'to_warehouse_id',
        'from_branch_id',
        'qty',
        'status_send',
        'status_received',
        'sent_by',
        'received_by',
        'from_po',
        'notes',
    ];


    public function fromWarehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'from_warehouse_id');
    }

    public function toWarehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'to_warehouse_id');
    }


    public function fromBranch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'from_branch_id');
    }

    public function toBranch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'to_branch_id');
    }

    public function po(): BelongsTo
    {
        return $this->belongsTo(GoodsPurchaseOrder::class, 'po_id');
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Goods::class, 'item_id');
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function sentBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sent_by');
    }

    public function receivedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'received_by');
    }
}
