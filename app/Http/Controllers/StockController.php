<?php

namespace App\Http\Controllers;

use AllowDynamicProperties;
use App\Models\DraftStock;
use App\Models\ItemCollection;
use App\Models\Master\Common\Branch;
use App\Models\Stock;
use App\Support\Inventory\StockManagement\Stock\Service\StockService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;


#[AllowDynamicProperties] class StockController extends Controller
{
    public function __construct()
    {
        $this->stockService = new StockService();
    }


    public function index(): View
    {
        return view('pages.inventory.stocks.index');
    }


    /**
     * @throws AuthorizationException
     */
    public function data(Request $request): JsonResponse
    {
        $this->authorize('view', Stock::class);
        return response()->json($this->stockService->data($request));
    }


    /**
     * @throws AuthorizationException
     */
    public function search(Request $request): JsonResponse
    {
        $this->authorize('view', Stock::class);
        return response()->json($this->stockService->searchGoodsData($request));
    }

    /**
     * @throws AuthorizationException
     */
    public function filter(Request $request): JsonResponse
    {
        $this->authorize('view', Stock::class);
        return response()->json($this->stockService->filter($request));
    }

    /**
     * @throws AuthorizationException
     */
    public function detail(ItemCollection $goods): View
    {
        $this->authorize('view', Stock::class);
        return view('pages.inventory.goods.stocks.detail.index', compact('goods'));
    }


    public function edit(Stock $stock): JsonResponse
    {
        $this->authorize('view', Stock::class);
        return response()->json($stock);
    }


    public function show(ItemCollection $itemCollection): JsonResponse
    {
        $this->authorize('view', Stock::class);
        return response()->json($itemCollection->load('category'));
    }


    public function destroy(Request $request, Stock $goodsStock): JsonResponse
    {
        $this->authorize('view', Stock::class);
        $implodeID = implode(',', $request->get('id'));
        $explodeID = explode(',', $implodeID);
        $goodsStock->whereIn('id', $explodeID)->delete();

        return response()->json([
            'message' => 'data berhasil dihapus',
        ]);
    }

    /**
     * @throws AuthorizationException
     */
    public function confirm(Stock $goodsStock, Request $request): JsonResponse
    {
        $this->authorize('view', Stock::class);
        $implodeID = implode(',', $request->get('id'));
        $explodeID = explode(',', $implodeID);
        $goodsStock->whereIn('id', $explodeID)->update(['status' => 1]);

        return response()->json([
            'message' => 'data berhasil dihapus',
        ]);
    }

}
