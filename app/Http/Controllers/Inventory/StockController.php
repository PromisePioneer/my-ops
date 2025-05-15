<?php

namespace App\Http\Controllers\Inventory;

use AllowDynamicProperties;
use App\Http\Controllers\Controller;
use App\Models\DraftStock;
use App\Models\ItemCollection;
use App\Models\Master\Common\Branch;
use App\Models\Stock;
use App\Support\Inventory\StockManagement\Stock\Service\StockService;
use Illuminate\Auth\Access\AuthorizationException;
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
        return view('pages.inventory.stocks.index');
    }


    /**
     * @throws AuthorizationException
     */
    public function data(): JsonResponse
    {
        $this->authorize('view', Stock::class);
        return response()->json($this->stockService->data());
    }


    /**
     * @throws AuthorizationException
     */
    public function goodsSearch(Request $request): JsonResponse
    {
        $this->authorize('view', Stock::class);
        return response()->json($this->stockService->searchGoodsData($request));
    }

    /**
     * @throws AuthorizationException
     */
    public function goodsFilter(Request $request): JsonResponse
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


    /**
     * @throws AuthorizationException
     */
    public function edit(Stock $stock): JsonResponse
    {
        $this->authorize('view', Stock::class);
        return response()->json($stock);
    }


    /**
     * @throws AuthorizationException
     */
    public function show(ItemCollection $itemCollection): JsonResponse
    {
        $this->authorize('view', Stock::class);
        return response()->json($itemCollection->load('category'));
    }


    /**
     * @throws AuthorizationException
     */
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


    /**
     * @throws AuthorizationException
     */
    public function getMainBranchWithStock(ItemCollection $itemCollection): JsonResponse
    {
        $this->authorize('view', Stock::class);
        return response()->json($this->stockService->getMainBranchWithStock($itemCollection));
    }


    public function findByDraftStockAndItemName(DraftStock $draftStock): JsonResponse
    {
        $stocks = $this->stockService->findByDraftStockAndItemName($draftStock);
        return response()->json($stocks);
    }


    public function getMustReorderStocks(): JsonResponse
    {
        $stocks = $this->stockService->getMustReorderStocks();
        return response()->json($stocks);
    }


    public function getStockWithCodes(): JsonResponse
    {
        $stocks = $this->stockService->getStockWithCodes();
        return response()->json($stocks);
    }


    public function getStockWithoutCode(Request $request): JsonResponse
    {
        $stocks = $this->stockService->getStockWithoutCode($request);
        return response()->json($stocks);
    }


    public function findByItemAndBranch(Branch $branch, ItemCollection $itemCollection): JsonResponse
    {
        $stocks = $this->stockService->findByItemAndBranch($branch, $itemCollection);
        return response()->json($stocks);
    }
}
