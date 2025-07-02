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


    /**
     * @throws AuthorizationException
     */
    public function getMainBranchWithStock(ItemCollection $itemCollection)
    {
        $this->authorize('view', Stock::class);
        $branch = Branch::with('stock', 'children')->whereNull('parent_id')->get();


        return $branch->map(function ($item) use ($itemCollection) {
            return [
                'id' => $item?->id,
                'text' => $item?->name,
                'children' => $item->children->map(function ($child) use ($itemCollection) {
                    if (empty(Auth::user()->branch_id)) {
                        $totalQty = $child->name . ' - ' . 'Stock : ' . $child->stock->where('item_id', $itemCollection->id)->sum('qty');
                    } else {
                        $totalQty = $child->name;
                    }


                    return [
                        'id' => $child?->id,
                        'text' => $totalQty,
                    ];
                })
            ];
        });
    }


    public function findByDraftStockAndItemName(DraftStock $draftStock)
    {
        $stock = Stock::with('transaction', 'branch', 'item', 'initialInventoryBalance')->whereHas('item', function ($query) use ($draftStock) {
            $query->where('name', $draftStock->transaction?->item->name ?? $draftStock->initialInventoryBalance?->item->name);
        })->where(function ($query) use ($draftStock) {
            if ($draftStock->transaction_id) {
                $query->where('transaction_id', $draftStock->transaction_id);
            } else {
                $query->where('initial_balance_inventory_id', $draftStock->initial_balance_inventory_id);
            }
        })->get();


        return $stock->map(function ($stock) {
            $stockQty = $stock->qty . ' ' . $stock->item->unitType->name;
            if ($stock->item->unitType->name == 'Meter') {
                $stockQty = "{$stock->qty} Haspel / Unit";
            }

            $onHoldQty = $stock->on_hold_qty . ' ' . $stock->item->unitType->name;
            if ($stock->item->unitType->name == 'Meter') {
                $onHoldQty = "{$stock->on_hold_qty} Haspel / Unit";
            }

            return [
                'id' => $stock->id,
                'transaction_number' => $stock->transaction?->transaction_number ?? 'Persediaan Awal',
                'name' => $stock->item->name,
                'qty' => $stockQty,
                'condition' => $stock->condition,
                'on_hold_qty' => $onHoldQty,
                'available_qty' => $stock->qty - $stock->on_hold_qty . ' ' . $stock->item->unitType->name,
            ];
        });
    }


    public function getMustReorderStocks(Request $request): JsonResponse
    {
        return response()->json($this->stockService->getMustReorderStocks($request));
    }


    public function findByItemAndBranch(Branch $branch, ItemCollection $itemCollection): JsonResponse
    {
        $stocks = $this->stockService->findByItemAndBranch($branch, $itemCollection);
        return response()->json($stocks);
    }


    public function findByItemId(Request $request, ItemCollection $itemCollection): JsonResponse
    {
        return response()->json($this->stockService->findByItemId($request, $itemCollection));
    }


    public function getStockByCategoryAndBranch(Request $request): JsonResponse
    {
        $branch = $request->input('branch_id');
        $category = $request->input('category_id');
        return response()->json($this->stockService->getStockByCategoryAndBranch($branch, $category));
    }

    public function showStock(Stock $stock): JsonResponse
    {
        $stock->load('transaction.item');
        return response()->json($stock);
    }

    public function searchByCategoryAndBranch(Request $request): JsonResponse
    {
        $search = $request->get('search');
        $branch = $request->get('branch_id');
        $category = $request->get('category_id');
        return response()->json($this->stockService->searchByCategoryAndBranch($search, $branch, $category));
    }
}
