<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BranchWarehouseItem extends Model
{
    protected $table = 'branch_warehouse_items';
    protected $fillable = [
        'date',
        'po_id',
        'central_warehouse_stock_id',
        'branch_id',
        'item_id',
        'code',
        'qty',
        'unit_type_id'
    ];


    public function po(): BelongsTo
    {
        return $this->belongsTo(GoodsPurchaseOrder::class, 'po_item_id');
    }

    public function centralWarehouseStock(): BelongsTo
    {
        return $this->belongsTo(CentralWarehouseItem::class, 'central_warehouse_stock_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }


    public function item(): BelongsTo
    {
        return $this->belongsTo(Goods::class, 'item_id');
    }


    public function branchWarehouseStock(): HasMany
    {
        return $this->hasMany(BranchWarehouseStock::class, 'branch_warehouse_item_id');
    }


}
