<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Pagination\LengthAwarePaginator;

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
        'goods'
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
