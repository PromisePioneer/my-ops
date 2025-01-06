<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BranchWarehouseItem extends Model
{
    protected $table = 'branch_warehouse_items';
    protected $fillable = [
        'po_item_id',
        'central_warehouse_stock_id',
        'branch_id',
        'item_id',
        'code',
        'qty',
        'unit_type_id'
    ];


    public function po(): BelongsTo
    {
        return $this->belongsTo(PoListOfItem::class, 'po_item_id');
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
        return $this->belongsTo(Item::class, 'item_id');
    }


    public function unitType(): BelongsTo
    {
        return $this->belongsTo(UnitType::class, 'unit_type_id');
    }


}
