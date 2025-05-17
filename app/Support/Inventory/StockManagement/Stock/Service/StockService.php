<?php

namespace App\Support\Inventory\StockManagement\Stock\Service;

use AllowDynamicProperties;
use App\Models\DraftStock;
use App\Models\ItemCollection;
use App\Models\Master\Common\Branch;
use App\Support\Inventory\StockManagement\Stock\Repository\StockRepository;
use App\Support\Master\Common\Branch\Repository\BranchRepository;
use App\Support\Master\Operational\ItemCollections\Repositories\ItemCollectionRepository;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

#[AllowDynamicProperties] class StockService
{
    private static int $perPage = 10;


    public function __construct()
    {
        $this->itemCollectionRepository = new ItemCollectionRepository();
        $this->branchRepository = new BranchRepository();
        $this->stockRepository = new StockRepository();
    }

    public function data(): LengthAwarePaginator
    {
        $goods = $this->itemCollectionRepository->itemCollectionStock()->paginate(self::$perPage);
        return self::formattedGoodsData($goods);
    }


    public function searchGoodsData(Request $request): LengthAwarePaginator
    {
        $search = $request->input('search');
        $goods = $this->itemCollectionRepository->itemCollectionStock()
            ->when(!empty($search), function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            })->paginate(self::$perPage);
        return self::formattedGoodsData($goods);
    }


    public function filter(Request $request): LengthAwarePaginator
    {
        $query = ItemCollection::with('stock');
        return self::formattedGoodsData(StockQueryFilter::apply($query, $request)->paginate(self::$perPage));
    }


    private static function formattedGoodsData(LengthAwarePaginator $goodsData): LengthAwarePaginator
    {
        $data = $goodsData->getCollection()->map(function ($item) {
            if (Auth::user()->branch_id) {
                $stock = $item->stock->where('branch_id', Auth::user()->branch_id)->sum('qty');
            } else {
                $stock = $item->stock->sum('qty');
            }

            return [
                'id' => $item->id,
                'type' => $item->type,
                'total_stock' => $stock,
                'category_name' => $item->category->name,
                'name' => $item->name,
                'unit_name' => $item->unitType->name
            ];
        });

        $goodsData->setCollection($data);
        return $goodsData;
    }


    // masih salah
    public function getMustReorderStocks()
    {
        $itemDoesntHaveGoodsStock = ItemCollection::whereDoesntHave('stock')->count();
        $items = ItemCollection::all();
        $itemHasGoodsStock = null;
        foreach ($items as $item) {
            $itemHasGoodsStock = ItemCollection::whereHas('stock', function ($query) use ($item) {
                $query->where('qty', '<', $item->reorder_level);
            })->count();
        }

        return $itemDoesntHaveGoodsStock + $itemHasGoodsStock;
    }


    public function findByItemAndBranch(Branch $branch, ItemCollection $itemCollection)
    {
        $stocks = $this->stockRepository->findByItemAndBranch($branch->id, $itemCollection->id)->get();
        return $stocks->flatMap(function ($stock) {
            return $stock->itemCatalog->map(function ($itemCatalog) use ($stock) {
                return [
                    'id' => $itemCatalog->id,
                    'branch_name' => $stock->branch->name,
                    'name' => $stock->item->name,
                    'code' => $itemCatalog->code,
                    'condition' => $itemCatalog->condition,
                ];
            });
        });
    }

    public function getMainBranchWithStock(ItemCollection $itemCollection)
    {
        $branch = $this->branchRepository->getBranchWithStock()->get();
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


    public function getStockWithCodes()
    {
        $stock = $this->stockRepository->getStockWithCodes()->get();
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
        $stock = $this->stockRepository->getStockWithoutCode($request)->get();
        return $stock->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->item->name,
                'text' => "{$item->item->name} Stok : {$item->qty} {$item->condition}",
            ];
        });
    }

    public function findByDraftStockAndItemName(DraftStock $draftStock)
    {
        $draftStock->load('transaction.item');
        $stock = $this->stockRepository->findByDraftStockAndItemName($draftStock)->get();
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

}
