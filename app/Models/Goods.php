<?php

namespace App\Models;

use App\Models\Master\Common\UnitType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Scout\Searchable;

class Goods extends Model
{

    use Searchable;
    protected $table = 'goods';
    protected $fillable = [
        'name',
        'category_id',
        'unit_type_id',
        'material'
    ];


    public function toSearchableArray(): array
    {
        return [
            'name' => $this->name
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
        return $this->belongsTo(GoodsCategory::class, 'category_id');
    }


}
