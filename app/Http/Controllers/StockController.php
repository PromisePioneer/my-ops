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
    public function data(): JsonResponse
    {
        $this->authorize('view', Stock::class);
        return response()->json($this->stockService->data());
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
        $stock = Stock::with('transaction', 'branch', 'item')->whereHas('item', function ($query) use ($draftStock) {
            $query->where('name', $draftStock->transaction->item->name);
        })->where('transaction_id', $draftStock->transaction_id)
            ->orWhere('initial_balance_inventory_id', $draftStock->initial_balance_inventory_id)
            ->get();
        return $stock->map(function ($stock) {
            return [
                'id' => $stock->id,
                'transaction_number' => $stock->transaction?->transaction_number ?? 'Persediaan Awal',
                'name' => $stock->item->name,
                'qty' => $stock->qty . ' ' . $stock->item->unitType->name,
                'condition' => $stock->condition,
                'on_hold_qty' => $stock->on_hold_qty . ' ' . $stock->item->unitType->name,
                'available_qty' => $stock->qty - $stock->on_hold_qty . ' ' . $stock->item->unitType->name,
            ];
        });
    }


    public function getMustReorderStocks(): JsonResponse
    {
        return response()->json($this->stockService->getMustReorderStocks());
    }


    public function getStockWithCodes(Request $request)
    {
        $stock = Stock::with('item', 'itemCatalog', 'branch')
            ->where(function ($query) use ($request) {
                $query->whereHas('branch', function ($query) use ($request) {
                    $query->where('parent_id', $request->branch_id ?? $request->user()->branch_id);
                })->whereHas('item.category', function ($query) {
                    $query->where('name', '!=', 'Kategori 4');
                })->where('condition', 'Baik');
            })->get();


        return $stock->map(function ($stock) {
            $itemCatalog = [];
            foreach ($stock->itemCatalog->where('status', 'Tersedia') as $value) {
                $itemCatalog[] = [
                    'id' => $value->id,
                    'stock_id' => $stock->id,
                    'code' => $value->code,
                    'text' => 'SN: ' . $value->code,
                ];
            }


            return [
                'id' => $stock->id,
                'text' => $stock->item->name,
                'children' => $itemCatalog
            ];
        });
    }


    public function getStockWithoutCode(Request $request)
    {
        $stock = Stock::with('item', 'branch')
            ->whereHas('branch', function ($query) use ($request) {
                $query->where('parent_id', $request->branch_id ?? $request->user()->branch_id);
            })
            ->whereHas('item.category', function ($query) {
            $query->where('name', 'Kategori 4');
        })->whereNotIn('id', $request->get('ids', []))
            ->whereIn('condition', ['Baik', 'Diperbaiki'])
            ->get();


        return $stock->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->item->name,
                'text' => $item->item->name . ' Stok : ' . $item->qty . ' - ' . $item->condition,
            ];
        });
    }


    public function findByItemAndBranch(Branch $branch, ItemCollection $itemCollection): JsonResponse
    {
        $stocks = $this->stockService->findByItemAndBranch($branch, $itemCollection);
        return response()->json($stocks);
    }
}
