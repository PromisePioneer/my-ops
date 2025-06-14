<?php

namespace App\Support\Inventory\StockManagement\Stock\Service;

use AllowDynamicProperties;
use App\Models\ItemCollection;
use App\Models\Master\Common\Branch;
use App\Models\Stock;
use App\Support\Inventory\StockManagement\DraftStock\Repository\DraftStockRepository;
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
                $stock = $item->transaction?->stock->where('branch_id', Auth::user()->branch_id)->sum('qty') ?? $item->initialInventoryBalance?->stock->where('branch_id', Auth::user()->branch_id)->sum('qty');
            } else {
                $stock = DraftStockRepository::draftStockQtySumByItemId($item->id)
                    + StockRepository::getSumStockQtyByItemId($item->id);
            }

            return [
                'id' => $item->id,
                'type' => $item->type,
                'total_stock' => $stock,
                'category_name' => $item->category?->name,
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
        return $this->calculateDraftStockAndStock();
    }


    public function calculateDraftStockAndStock(): ?int
    {
        $itemCollections = ItemCollection::query()->get();
        $totalItemMustReorder = 0;


        foreach ($itemCollections as $itemCollection) {
            $totalStock = DraftStockRepository::draftStockQtySumByItemId($itemCollection->id)
                + StockRepository::getSumStockQtyByItemId($itemCollection->id);


            if ($totalStock < $itemCollection->reorder_level) {
                $totalItemMustReorder++;
            }
        }

        return $totalItemMustReorder;
    }


    public function findByItemAndBranch(Branch $branch, ItemCollection $itemCollection)
    {
        $stocks = $this->stockRepository->findByItemAndBranch($branch->id, $itemCollection->id)->get();
        return $stocks->map(function ($stock) {
            return [
                'id' => $stock->id,
                'branch_name' => $stock->stock->branch->name,
                'name' => $stock->stock->item->name,
                'code' => $stock->code,
                'condition' => $stock->condition,
            ];
        });
    }


    public function findByItemId(ItemCollection $itemCollection): LengthAwarePaginator
    {
        $query = $this->stockRepository->findByItemId($itemCollection)->paginate(self::$perPage);
        $data = $query->getCollection()->map(function ($item) {
            $unitType = $item->transaction?->item?->unitType?->name ?? $item->initialInventoryBalance->unitType?->name;

            return [
                'id' => $item->id,
                'transaction_number' => $item->transaction->transaction_number,
                'available_qty' => "$item->available_qty $unitType",
                'broken_qty' => "$item->broken_qty $unitType",
                'on_hold_qty' => "$item->on_hold_qty $unitType",
            ];
        });


        $query->setCollection($data);
        return $query;
    }

    public function getStockWithoutCode(Request $request)
    {

        $stock = Stock::with('item', 'branch')
            ->whereHas('branch', function ($query) use ($request) {
                $query->where('id', $request->branch_id ?? $request->user()->branch_id);
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

    public function getStockWithCode(Request $request)
    {
        $stock = Stock::with('transaction.item.category', 'initialInventoryBalance.item.category')
            ->where('branch_id', $request->branch_id ?? $request->user()->branch_id)
            ->where(function ($query) use ($request) {
                $query->whereHas('transaction.item.category', function ($query) {
                    $query->where('name', '!=', 'Kategori 4');
                })->orWhereHas('initialInventoryBalance.item.category', function ($query) {
                    $query->where('name', '!=', 'Kategori 4');
                });
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
                'text' => $stock->transaction?->item?->name ?? $stock->initialInventoryBalance?->item?->name,
                'children' => $itemCatalog
            ];
        });
    }

}
