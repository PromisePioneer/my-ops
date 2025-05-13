<?php

namespace App\Http\Controllers;

use AllowDynamicProperties;
use App\Models\DraftStock;
use App\Models\ItemCollection;
use App\Models\Master\Common\Branch;
use App\Models\Stock;
use App\Support\StockService;
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
        return view('pages.inventory.goods.stocks.index');
    }


    /**
     * @throws AuthorizationException
     */
    public function data(): JsonResponse
    {
        $this->authorize('view', Stock::class);
        return response()->json($this->stockService->goodsData());
    }


    public function goodsSearch(Request $request): JsonResponse
    {
        $this->authorize('view', Stock::class);
        return response()->json($this->stockService->searchGoodsData($request));
    }

    public function goodsFilter(Request $request): JsonResponse
    {
        $this->authorize('view', Stock::class);
        return response()->json($this->stockService->filter($request));
    }

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


    public function getStockBasedOnDraftStock(DraftStock $draftStock)
    {

        $draftStock->load('transaction.item');
        $stock = Stock::with('transaction', 'branch', 'item')
            ->whereHas('item', function ($query) use ($draftStock) {
                $query->where('name', $draftStock->transaction->item->name ?? $draftStock->initialInventoryBalance->item->name);
            })->where('draft_stock_id', $draftStock->id)
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


    public function getMustReorderStocks(Request $request): JsonResponse
    {
        return response()->json($this->stockService->getMustReorderStocks($request));
    }


    public function getStockWithCode()
    {
        $stock = Stock::with('item', 'itemCatalog')
            ->whereHas('item.category', function ($query) {
                $query->where('name', '!=', 'Kategori 4');
            })->when(!empty(Auth::user()->branch_id), function ($query) {
                $query->where('branch_id', Auth::user()->branch_id);
            })->where('condition', 'Baik')
            ->get();


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
        $stock = Stock::with('item')->whereHas('item.category', function ($query) {
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


    public function getStockBasedOnItemIdAndBranchId(Branch $branch, ItemCollection $itemCollection): JsonResponse
    {
        return response()->json($this->stockService->getStockBasedOnItemIdAndBranchId($branch->id, $itemCollection->id));
    }
}
