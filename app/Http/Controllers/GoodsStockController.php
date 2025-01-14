<?php

namespace App\Http\Controllers;

use App\Models\Goods;
use App\Models\GoodsStock;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GoodsStockController extends Controller
{


    private static int $perPage = 10;

    public function __construct()
    {

    }

    public function index(): View
    {
        return view('pages.inventory.goods.stocks.index');
    }


    public function data(): JsonResponse
    {
        $goods = Goods::with('goodsStock')->paginate(self::$perPage);
        return response()->json($goods);
    }
}
