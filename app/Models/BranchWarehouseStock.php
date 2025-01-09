<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BranchWarehouseStock extends Model
{
    protected $table = 'branch_warehouse_stocks';
    protected $fillable = [
        'branch_warehouse_item_id',
        'sn',
        'status'
    ];


    public function branchWarehouseItem()
    {
        return $this->belongsTo(BranchWarehouseItem::class, 'branch_warehouse_item_id');
    }
}
