<?php

namespace App\Models;

use App\Models\Master\Common\Branch;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DraftStock extends Model
{
    protected $table = 'draft_stocks';
    protected $fillable = [
        'transaction_id',
        'initial_balance_inventory_id',
        'qty',
    ];


    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }


    public function initialInventoryBalance(): BelongsTo
    {
        return $this->belongsTo(InitialInventoryBalance::class, 'initial_balance_inventory_id');
    }
}
