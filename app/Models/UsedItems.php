<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * 
 *
 * @property int $id
 * @property int $account_id
 * @property int $goods_id
 * @property int $created_by
 * @property int $total_used
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Goods $goods
 * @method static \Illuminate\Database\Eloquent\Builder|UsedItems newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UsedItems newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UsedItems query()
 * @method static \Illuminate\Database\Eloquent\Builder|UsedItems whereAccountId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UsedItems whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UsedItems whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UsedItems whereGoodsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UsedItems whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UsedItems whereTotalUsed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UsedItems whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class UsedItems extends Model
{
    protected $table = 'used_goods_history';

    protected $fillable = [
        'account_id',
        'goods_id',
        'created_by',
        'total_used',
    ];

    protected $with = [
        'goods',
    ];

    public function goods(): BelongsTo
    {
        return $this->belongsTo(Goods::class, 'goods_id');
    }

    //eloquent
    public function getDataWithPaginationBasedOnGoods(int $goodsId, int $perPage): LengthAwarePaginator
    {
        return self::with('goods')->where('goods_id', $goodsId)
            ->paginate($perPage);
    }
}
