<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BranchStock extends Model
{
    protected $table = 'branch_stock';
    protected $fillable = [
        'central_warehouse_stock_id',
        'branch_id',
        'code'
    ];


    public function centralWarehouseStock(): BelongsTo
    {
        return $this->belongsTo(CentralWarehouseStock::class, 'central_warehouse_stock_id');
    }


    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
