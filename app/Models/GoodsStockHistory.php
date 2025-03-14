<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoodsStockHistory extends Model
{
    protected $table = 'goods_stock_history';
    protected $fillable = [
        'branch_id',
        'goods_id',
        'qty',
    ];


    public function goods()
    {
        return $this->belongsTo(Goods::class, 'goods_id');
    }
}
