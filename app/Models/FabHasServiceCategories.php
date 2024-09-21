<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $fab_id
 * @property int $service_category_id
 * @property int $qty
 * @property float $unit_price
 * @property float $total_price
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Fab $fab
 * @property-read ServiceCategory $service
 *
 * @method static Builder|FabHasServiceCategories newModelQuery()
 * @method static Builder|FabHasServiceCategories newQuery()
 * @method static Builder|FabHasServiceCategories query()
 * @method static Builder|FabHasServiceCategories whereCreatedAt($value)
 * @method static Builder|FabHasServiceCategories whereFabId($value)
 * @method static Builder|FabHasServiceCategories whereId($value)
 * @method static Builder|FabHasServiceCategories whereQty($value)
 * @method static Builder|FabHasServiceCategories whereServiceCategoryId($value)
 * @method static Builder|FabHasServiceCategories whereTotalPrice($value)
 * @method static Builder|FabHasServiceCategories whereUnitPrice($value)
 * @method static Builder|FabHasServiceCategories whereUpdatedAt($value)
 *
 * @mixin Eloquent
 */
class FabHasServiceCategories extends Model
{
    protected $table = 'fab_has_service_categories';

    protected $fillable = [
        'fab_id',
        'service_category_id',
        'qty',
        'unit_price',
        'total_price',
    ];

    public function fab(): BelongsTo
    {
        return $this->belongsTo(Fab::class, 'fab_id');
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(ServiceCategory::class, 'service_category_id');
    }
}
