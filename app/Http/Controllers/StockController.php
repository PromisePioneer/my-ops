<?php

namespace App\Http\Controllers;

use AllowDynamicProperties;
use App\Http\Requests\GenerateItemSNRequest;
use App\Models\Goods;
use App\Models\Master\Common\Branch;
use App\Models\Stock;
use App\Support\StockService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

#[AllowDynamicProperties] class StockController extends Controller
{
    public function __construct()
    {
        $this->stockService = new StockService();
    }


    public function index(): View
    {
        return view('pages.inventory.goods.stocks.index');
    }


    public function data(): JsonResponse
    {
        return response()->json($this->stockService->goodsData());
    }


    public function goodsSearch(Request $request): JsonResponse
    {
        return response()->json($this->stockService->searchGoodsData($request));
    }

    public function goodsFilter(Request $request): JsonResponse
    {
        return response()->json($this->stockService->filter($request));
    }

    public function detail(Goods $goods): View
    {
        return view('pages.inventory.goods.stocks.detail.index', compact('goods'));
    }


    public function edit(Stock $stock): JsonResponse
    {
        return response()->json($stock);
    }


    public function show(Goods $goods): JsonResponse
    {
        return response()->json($goods);
    }




    public function destroy(Request $request, Stock $goodsStock): JsonResponse
    {
        $implodeID = implode(',', $request->get('id'));
        $explodeID = explode(',', $implodeID);
        $goodsStock->whereIn('id', $explodeID)->delete();

        return response()->json([
            'message' => 'data berhasil dihapus',
        ]);
    }

    public function confirm(Stock $goodsStock, Request $request): JsonResponse
    {
        $implodeID = implode(',', $request->get('id'));
        $explodeID = explode(',', $implodeID);
        $goodsStock->whereIn('id', $explodeID)->update(['status' => 1]);

        return response()->json([
            'message' => 'data berhasil dihapus',
        ]);
    }


    public function getMainBranchWithStock(Goods $goods)
    {
        $branch = Branch::whereHas('stock', function ($query) use ($goods) {
            $query->where('item_id', $goods->id);
        })->get();

        return $branch->map(function ($item) {
            return [
                'id' => $item->id,
                'text' => $item->name . ' - ' . $item->stock->sum('qty'),
            ];
        });
    }
}
