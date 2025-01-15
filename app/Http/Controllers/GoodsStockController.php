<?php

namespace App\Http\Controllers;

use AllowDynamicProperties;
use App\Http\Requests\GenerateItemSNRequest;
use App\Models\Goods;
use App\Models\GoodsPurchaseOrder;
use App\Models\GoodsStock;
use App\Service\GoodsStockService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

#[AllowDynamicProperties] class GoodsStockController extends Controller
{

    private static int $perPage = 10;


    public function __construct()
    {
        $this->goodsStockService = new GoodsStockService();
    }


    public function index(): View
    {
        return view('pages.inventory.goods.stocks.index');
    }


    public function data(): JsonResponse
    {
        return response()->json($this->goodsStockService->goodsData());
    }


    public function goodsSearch(Request $request): JsonResponse
    {
        return response()->json($this->goodsStockService->searchGoodsData($request));
    }

    public function detail(Goods $goods): View
    {
        return view('pages.inventory.goods.stocks.detail.index', compact('goods'));
    }

    public function getPO(Goods $goods): JsonResponse
    {
        return response()->json($this->goodsStockService->getPO($goods));
    }

    public function searchPO(Request $request, Goods $goods): JsonResponse
    {
        return response()->json($this->goodsStockService->searchPO($request, $goods));
    }


    public function generateSN(GoodsPurchaseOrder $goodsPurchaseOrder): View
    {
        return view('pages.inventory.goods.stocks.generate-sn.index', compact('goodsPurchaseOrder'));
    }


    public function PODetail(GoodsPurchaseOrder $goodsPurchaseOrder): JsonResponse
    {
        $goodsPurchaseOrder = GoodsPurchaseOrder::with('item')->find($goodsPurchaseOrder->id);
        return response()->json($goodsPurchaseOrder);
    }


    public function getGoodsStockBasedOnPO(GoodsPurchaseOrder $goodsPurchaseOrder): JsonResponse
    {
        $data = GoodsStock::where('po_id', $goodsPurchaseOrder->id)->paginate(self::$perPage);
        return response()->json($data);
    }


    public function createSN(GoodsPurchaseOrder $goodsPurchaseOrder, GenerateItemSNRequest $request): JsonResponse
    {
        GoodsStock::create([
            'po_id' => $goodsPurchaseOrder->id,
            'warehouse_id' => $goodsPurchaseOrder->warehouse_id,
            'branch_id' => $goodsPurchaseOrder->branch_id,
            'item_id' => $goodsPurchaseOrder->item_id,
            'qty' => $goodsPurchaseOrder->qty,
            'sn' => $request->sn
        ]);

        return response()->json([
            'message' => 'Data berhasil disimpan.'
        ]);
    }


    public function edit(GoodsStock $goodsStock): JsonResponse
    {
        return response()->json($goodsStock);
    }


    public function update(GoodsStock $goodsStock, GenerateItemSNRequest $request): JsonResponse
    {
        $goodsStock->update([
            'sn' => $request->sn
        ]);

        return response()->json(['message' => 'data berhasil disimpan']);
    }


    public function destroy(Request $request, GoodsStock $goodsStock): JsonResponse
    {
        $implodeID = implode(',', $request->get('id'));
        $explodeID = explode(',', $implodeID);
        $goodsStock->whereIn('id', $explodeID)->delete();

        return response()->json([
            'message' => 'data berhasil dihapus',
        ]);
    }

    public function confirm(GoodsStock $goodsStock, Request $request): JsonResponse
    {
        $implodeID = implode(',', $request->get('id'));
        $explodeID = explode(',', $implodeID);
        $goodsStock->whereIn('id', $explodeID)->update(['status' => 1]);

        return response()->json([
            'message' => 'data berhasil dihapus',
        ]);
    }
}
