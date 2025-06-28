<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ItemCatalog extends Model
{
    protected $table = 'item_catalogs';
    protected $fillable = [
        'stock_id',
        'asset_id',
        'code',
        'condition',
        'status',
        'created_by',
        'available_qty',
        'broken_qty'
    ];


    public function draftStock(): BelongsTo
    {
        return $this->belongsTo(DraftStock::class, 'draft_stock_id');
    }

    public function stock(): BelongsTo
    {
        return $this->belongsTo(Stock::class, 'stock_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }


    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class, 'asset_id');
    }
}
