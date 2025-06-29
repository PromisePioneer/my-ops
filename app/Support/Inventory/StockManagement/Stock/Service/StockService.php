<?php

namespace App\Support\Inventory\StockManagement\Stock\Service;

use AllowDynamicProperties;
use App\Models\ItemCategory;
use App\Models\ItemCollection;
use App\Models\Master\Common\Branch;
use App\Support\Inventory\StockManagement\DraftStock\Repository\DraftStockRepository;
use App\Support\Inventory\StockManagement\Stock\Repository\StockRepository;
use App\Support\Master\Common\Branch\Repository\BranchRepository;
use App\Support\Master\Operational\ItemCollections\Repositories\ItemCollectionRepository;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

#[AllowDynamicProperties] class StockService
{
    private static int $perPage = 10;

    public function __construct()
    {
        $this->itemCollectionRepository = new ItemCollectionRepository();
        $this->branchRepository = new BranchRepository();
        $this->stockRepository = new StockRepository();
    }

    public function data(Request $request): LengthAwarePaginator
    {
        $goods = $this->itemCollectionRepository->itemCollectionStock()->paginate(self::$perPage);
        return self::formattedGoodsData($request, $goods);
    }


    public function searchGoodsData(Request $request): LengthAwarePaginator
    {
        $search = $request->input('search');
        $goods = $this->itemCollectionRepository->itemCollectionStock()
            ->when(!empty($search), function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            })->paginate(self::$perPage);
        return self::formattedGoodsData($request, $goods);
    }


    public function filter(Request $request): LengthAwarePaginator
    {
        $query = ItemCollection::with('stock');
        return self::formattedGoodsData($request, StockQueryFilter::apply($query, $request)->paginate(self::$perPage));
    }


    private static function formattedGoodsData(Request $request, LengthAwarePaginator $goodsData): LengthAwarePaginator
    {
        $data = $goodsData->getCollection()->map(function ($item) use ($request) {
            $totalReadyStock = StockRepository::getSumStockQtyByItemId($request, $item->id)->sum('available_qty');
            $totalOnHoldQty = StockRepository::getSumStockQtyByItemId($request, $item->id)->sum('on_hold_qty');
            $totalBrokenQty = StockRepository::getSumStockQtyByItemId($request, $item->id)->sum('broken_qty');

            return [
                'id' => $item->id,
                'type' => $item->type,
                'total_stock' => "$totalReadyStock {$item->unitType->name}",
                'category_name' => $item->category?->name,
                'total_on_hold_qty' => "$totalOnHoldQty {$item->unitType->name}",
                'total_broken_qty' => "$totalBrokenQty {$item->unitType->name}",
                'name' => $item->name,
                'unit_name' => $item->unitType->name
            ];
        });

        $goodsData->setCollection($data);
        return $goodsData;
    }


    // masih salah
    public function getMustReorderStocks(Request $request): ?int
    {
        return $this->calculateDraftStockAndStock($request);
    }


    public function calculateDraftStockAndStock(Request $request): ?int
    {
        $itemCollections = ItemCollection::query()->get();
        $totalItemMustReorder = 0;


        foreach ($itemCollections as $itemCollection) {
            $totalStock = DraftStockRepository::draftStockQtySumByItemId($request, $itemCollection->id)
                + StockRepository::getSumStockQtyByItemId($request, $itemCollection->id)->sum('available_qty');


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
            $unitType = $item->transaction?->item?->unitType?->name ?? $item->initialInventoryBalance->item?->unitType?->name;

            return [
                'id' => $item->id,
                'branch_name' => "{$item->branch->parent->name} -  {$item->branch->name}",
                'transaction_number' => $item->transaction?->transaction_number ?? 'Persediaan Awal',
                'available_qty' => "$item->available_qty $unitType",
                'broken_qty' => "$item->broken_qty $unitType",
                'on_hold_qty' => "$item->on_hold_qty $unitType",
            ];
        });


        $query->setCollection($data);
        return $query;
    }


    public function getStockByCategoryAndBranch(string|int $branchId, string|int $categoryId): LengthAwarePaginator
    {
        $query = $this->stockRepository->getStockByCategoryAndBranch($branchId, $categoryId)->paginate(self::$perPage);
        $itemCategory = ItemCategory::find($categoryId);

        if ($itemCategory->name !== 'Kategori 4') {
            $data = $query->getCollection()->map(function ($itemCatalog) {
                $item = $itemCatalog?->stock?->transaction?->item
                    ?? $itemCatalog?->stock?->initialInventoryBalance?->item;
                return [
                    'id' => $itemCatalog->id,
                    'code' => $itemCatalog->code,
                    'name' => $item->name,
                    'item_id' => $item->id,
                    'qty' => $itemCatalog->available_qty,
                    'stock_id' => $itemCatalog->stock_id,
                    'category_name' => $item->category?->name,
                ];
            });
            $query->setCollection($data);
            return $query;
        }


        $data = $query->getCollection()->map(function ($stock) {
            $qty = 0;
            if (session()->has('stock_withdrawal_item')) {
                foreach (session()->get('stock_withdrawal_item') as $stockWithdrawalItem) {
                    if ($stock->id === (int)$stockWithdrawalItem['stock_id']) {
                        $qty += $stockWithdrawalItem['qty'];
                    }
                }
            }


            if (session()->has('stock_mutation_items')) {
                foreach (session()->get('stock_mutation_items') as $stockMutationItem) {
                    if ($stock->id === (int)$stockMutationItem['stock_id']) {
                        $qty += $stockMutationItem['qty'];
                    }
                }
            }

            return [
                'id' => $stock->id,
                'name' => $stock->transaction?->item->name ?? $stock->initialInventoryBalance?->item->name,
                'qty' => $stock->available_qty - $qty,
                'category_name' => $stock->transaction->item->category?->name ?? $stock->initialInventoryBalance->category?->name,
            ];
        });

        $query->setCollection($data ?? []);
        return $query;

    }

    public function searchByCategoryAndBranch(?string $search, int $branchId, int $categoryId): LengthAwarePaginator
    {
        $query = $this->stockRepository->getStockByCategoryAndBranch($branchId, $categoryId);

        $code = [];
        $code2 = [];
        if (session()->has('stock_withdrawal_item')) {
            foreach (session()->get('stock_withdrawal_item') as $withDrawalItem) {
                $code[] = $withDrawalItem['code'];
            }
        }


        if (session()->has('stock_mutation_items')) {
            foreach (session()->get('stock_mutation_items') as $itemMutation) {
                $code2[] = $itemMutation['code'];
            }
        }


        $data = $query->paginate(self::$perPage);

        $transformed = $data->getCollection()->flatMap(function ($stock) use ($code, $code2, $search) {
            $item = $stock->transaction?->item ?? $stock->initialInventoryBalance?->item;
            $itemCategoryName = $item?->category?->name;
            $result = [];

            if ($itemCategoryName !== 'Kategori 4') {
                $filteredCatalogs = $stock->itemCatalog
                    ->where('status', 'Tersedia')
                    ->whereNotIn('code', $code)->whereNotIn('code', $code2);

                if (!empty($search)) {
                    $filteredCatalogs = $filteredCatalogs->filter(function ($catalog) use ($search) {
                        $itemName = $catalog->stock->transaction?->item?->name ?? '';
                        return stripos($catalog->code, $search) !== false || stripos($itemName, $search) !== false;
                    });
                }

                foreach ($filteredCatalogs as $itemCatalog) {
                    $result[] = [
                        'id' => $itemCatalog->id,
                        'code' => $itemCatalog->code,
                        'name' => $item->name,
                        'item_id' => $item->id,
                        'qty' => $itemCatalog->available_qty,
                        'stock_id' => $stock->id,
                        'category_name' => $itemCategoryName,
                    ];
                }
            } else {
                $qty = 0;
                if (session()->has('stock_withdrawal_item')) {
                    foreach (session()->get('stock_withdrawal_item') as $stockWithdrawalItem) {
                        if ($stock->id === (int)$stockWithdrawalItem['stock_id']) {
                            $qty += $stockWithdrawalItem['qty'];
                        }
                    }
                }

                $stockActualQty = $stock->available_qty - $qty;

                if (empty($search) || stripos($item->name, $search) !== false) {
                    $result[] = [
                        'id' => $stock->id,
                        'name' => $item->name,
                        'qty' => $stockActualQty,
                        'category_name' => $itemCategoryName,
                    ];
                }
            }

            return $result;
        });

        return $data->setCollection(collect($transformed));
    }

}
