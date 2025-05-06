<?php

namespace App\Models;

use App\Models\Master\Common\UnitType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Scout\Searchable;

class ItemCollection extends Model
{

    use Searchable;

    protected $table = 'item_collections';
    protected $fillable = [
        'name',
        'type',
        'code',
        'category_id',
        'unit_type_id',
        'material',
        'asset_account_id',
        'reorder_level',
        'must_have_code',
        'is_code_listed',
    ];


    public function toSearchableArray(): array
    {
        return [
            'name' => $this->name,
            'material' => $this->material,
        ];
    }

    public function goodsStock(): HasMany
    {
        return $this->hasMany(Stock::class, 'item_id');
    }

    public function unitType(): BelongsTo
    {
        return $this->belongsTo(UnitType::class, 'unit_type_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ItemCategory::class, 'category_id');
    }


    public function assetAccount(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'asset_account_id');
    }

    public function transaction(): HasMany
    {
        return $this->hasMany(Transaction::class, 'item_id');
    }
}
