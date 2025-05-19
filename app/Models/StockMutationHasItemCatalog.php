<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockMutationHasItemCatalog extends Model
{
    protected $table = 'mutation_histories_item_catalogs';
    protected $fillable = [
        'mutation_histories_id',
        'item_catalog_id',
    ];


    public function itemCatalog(): BelongsTo
    {
        return $this->belongsTo(ItemCatalog::class, 'item_catalog_id');
    }


    public function mutationHistory(): BelongsTo
    {
        return $this->belongsTo(StockMutationHistory::class, 'mutation_histories_id');
    }
}
