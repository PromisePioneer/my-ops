<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockMutationItem extends Model
{
    protected $table = 'stock_mutation_items';
    protected $fillable = [
        'stock_mutation_id',
        'stock_id',
        'code',
        'qty',
    ];


    public function stockMutation(): BelongsTo
    {
        return $this->belongsTo(StockMutation::class, 'stock_mutation_id');
    }


    public function stock(): BelongsTo
    {
        return $this->belongsTo(Stock::class, 'stock_id');
    }

    public function itemCatalog(): BelongsTo
    {
        return $this->belongsTo(ItemCatalog::class, 'item_catalog_id');
    }


    public function mutationHistory(): BelongsTo
    {
        return $this->belongsTo(StockMutation::class, 'mutation_histories_id');
    }
}
