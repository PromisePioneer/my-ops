<?php

namespace App\Http\Controllers;

use AllowDynamicProperties;
use App\App\Controllers\Controller;
use App\Models\Goods;
use App\Models\ConsumedStock;
use App\Support\Inventory\Stock\UsedStockRepository;
use Illuminate\Http\JsonResponse;

#[AllowDynamicProperties] class UsedStockDetailController extends Controller
{

    private static int $perPage = 10;

    public function __construct()
    {
        $this->usedStockRepository = new UsedStockRepository();
    }


    public function data(Goods $goods): JsonResponse
    {
        $stock = ConsumedStock::with('goods', function ($query) use ($goods) {
            $query->where('goods_id', $goods->id);
        }, 'transaction');
        return response()->json($stock);
    }
}
