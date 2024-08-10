<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $fab_id
 * @property int $service_category_id
 * @property int $qty
 * @property float $unit_price
 * @property float $total_price
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Fab $fab
 * @property-read \App\Models\ServiceCategory $service
 *
 * @method static \Illuminate\Database\Eloquent\Builder|FabService newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FabService newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FabService query()
 * @method static \Illuminate\Database\Eloquent\Builder|FabService whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FabService whereFabId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FabService whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FabService whereQty($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FabService whereServiceCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FabService whereTotalPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FabService whereUnitPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FabService whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class FabService extends Model
{
    protected $table = 'fab_services';

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
