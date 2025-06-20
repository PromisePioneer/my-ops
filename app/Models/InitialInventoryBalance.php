<?php

namespace App\Models;

use App\Models\Master\Common\Branch;
use App\Models\Master\Common\Contact;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InitialInventoryBalance extends Model
{
    protected $table = 'initial_inventory_balance';

    protected $fillable = [
        'branch_id',
        'date',
        'contact_id',
        'item_id',
        'qty',
        'unit_price',
        'stock_account_id',
        'total_price',
        'detail',
        'attachment',
        'status',
        'qty_in_meter'
    ];


    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }


    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Contact::class, 'contact_id');
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(ItemCollection::class, 'item_id');
    }


    public function stockAccount(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'stock_account_id');
    }
}
