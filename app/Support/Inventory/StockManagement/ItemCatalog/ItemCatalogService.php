<?php

namespace App\Support\Inventory\StockManagement\ItemCatalog;

use AllowDynamicProperties;
use App\Http\Requests\ItemCatalogRequest;
use App\Models\Asset;
use App\Models\DraftStock;
use App\Models\ItemCatalog;
use App\Models\ItemCollection;
use App\Models\Stock;
use App\Support\HelperService\UsefulLifeService;
use App\Support\Inventory\StockManagement\DraftStock\Repository\ItemCatalogRepository;
use App\Support\Inventory\StockManagement\Stock\Repository\StockRepository;
use App\Support\Inventory\StockManagement\StockWithdrawal\Repository\StockWithdrawalItemRepository;
use App\Support\Master\Accounting\Accounts\Repositories\AccountRepository;
use App\Support\Master\Accounting\Assets\Service\AssetService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Throwable;

#[AllowDynamicProperties] class ItemCatalogService
{

    private static int $perPage = 10;

    public function __construct()
    {
        $this->itemCatalogRepository = new ItemCatalogRepository();
        $this->stockWithdrawalItemRepository = new StockWithdrawalItemRepository();
        $this->stockRepository = new StockRepository();
        $this->assetService = new AssetService();
    }

    public function formattedData(LengthAwarePaginator $catalog): LengthAwarePaginator
    {
        $data = $catalog->getCollection()->map(function ($query) {

            return [
                'id' => $query->id,
                'item_name' => $query->transaction?->item?->name
                    ?? $query->initialInventoryBalance->item->name,
                'code' => $query->code,
                'condition' => $query->condition,
                'created_at' => $query->created_at,
                'created_by' => $query->createdBy->name,
                'status' => $query->status,
                'qty' => $query->qty,
            ];
        });

        $catalog->setCollection($data);
        return $catalog;
    }


    /**
     * @throws Throwable
     */
    public function store(ItemCatalogRequest $request, DraftStock $draftStock): void
    {
        $draftStock->load('transaction', 'initialInventoryBalance');

        DB::transaction(function () use ($request, $draftStock) {
            $stock = $this->findOrCreateStock($draftStock, $request);
            $draftStock->decrement('qty');
            if ($draftStock->transaction?->item?->type === 'ASET' || $draftStock->initialInventoryBalance?->item?->type === 'ASET') {
                $asset = $this->insertAsset($request, $draftStock, $stock);
                self::insertItemCatalog($request, $draftStock, $stock, $asset);
            } else {
                self::insertItemCatalog($request, $draftStock, $stock);
            }
        });
    }


    public function findOrCreateStock(DraftStock $draftStock, Request $request): Stock
    {
        $stock = $this->stockRepository->findByDraftStock($draftStock);
        if (empty($stock)) {
            return self::insertStock($draftStock);
        }

        if ($request->condition === 'Rusak') {
            $stock->increment('broken_qty', $draftStock->transaction?->qty_in_meter ?? 1);
        } else {
            $stock->increment('available_qty', $draftStock->transaction?->qty_in_meter ?? 1);
        }

        return $stock;
    }


    /**
     * @throws Throwable
     */
    private function insertAsset(Request $request, DraftStock $draftStock, Stock $stock)
    {
        $itemObject = $draftStock->transaction?->item ?? $draftStock->initialInventoryBalance?->item;
        $asset = Asset::create([
            'branch_id' => $draftStock->transaction?->branch_id ?? $draftStock->initialInventoryBalance?->branch_id,
            'code' => $request->code,
            'stock_id' => $stock->id,
            'item_id' => $draftStock->transaction?->item_id ?? $draftStock->initialInventoryBalance?->item_id,
            'date' => $draftStock->transaction?->date ?? $draftStock->initialInventoryBalance?->date,
            'useful_life' => UsefulLifeService::getUsefulLife(
                AccountRepository::findByTransactionOrInitialInventoryBalanceId($draftStock)->code,
                $itemObject->non_building_group,
                $itemObject?->building_type
            ),
            'price' => $draftStock->transaction?->unit_price ?? $draftStock->initialInventoryBalance?->unit_price,
        ]);

        return $asset;
    }


    private static function insertStock(DraftStock $draftStock): Stock
    {
        return Stock::create([
            'branch_id' => $draftStock->transaction?->branch_id ?? $draftStock->initialInventoryBalance->branch_id,
            'transaction_id' => $draftStock->transaction_id,
            'initial_balance_inventory_id' => $draftStock->initial_balance_inventory_id,
            'on_hold_qty' => 0,
            'available_qty' => $draftStock->transaction?->qty_in_meter ?? 1,
            'broken_qty' => 0,
        ]);
    }


    private static function insertItemCatalog(Request $request, DraftStock $draftStock, ?Stock $stock, ?Asset $asset = null): ItemCatalog
    {
        return ItemCatalog::create([
            'stock_id' => $stock->id,
            'item_id' => $draftStock->transaction->item_id ?? $draftStock->initialInventoryBalance->item_id,
            'code' => $request->code,
            'asset_id' => $asset->id ?? null,
            'condition' => $request->condition,
            'created_by' => $request->user()->id,
            'available_qty' => $draftStock->transaction->qty_in_meter ?? 1,
            'broken_qty' => 0,
        ]);
    }


    /**
     * @throws Throwable
     */
    public function destroy(ItemCatalog $itemCatalog): void
    {
        $itemCatalog->load('stock.transaction', 'stock.initialInventoryBalance');
        $oldStock = $this->stockRepository->findByItemCatalog($itemCatalog);
        DB::transaction(function () use ($oldStock, $itemCatalog) {
            if (!empty($oldStock) && $itemCatalog->status === 'Tersedia') {
                DraftStock::where(
                    'id', $itemCatalog->stock?->transaction_id ?? $itemCatalog->stock?->initial_inventory_balance_id
                )->increment('qty');
                $oldStock->decrement('available_qty', $itemCatalog->available_qty + $itemCatalog->broken_qty);
                $itemCatalog->delete();
                Asset::where('id', $itemCatalog->asset_id)->delete();
            }
        });
    }

    public function generateAutomaticItemCode(DraftStock $draftStock): string
    {
        $draftStock->load('transaction.branch.parent', 'transaction.item', 'initialInventoryBalance.item');


        if ($draftStock->transaction->item->is_code_listed === 0 || $draftStock->initialInventoryBalance->item->is_code_listed === 0) {
            return '';
        }

        $latestItemCatalog = $this->itemCatalogRepository
            ->getLatestItem(
                $draftStock->transaction?->item_id
                ?? $draftStock->initialInventoryBalance?->item_id
            )->latest()
            ->first();
        $month = date('m');
        $year = date('y');


        $code = $draftStock->transaction->item->code ?? $draftStock->initialInventoryBalance->item->code;
        $branchCode = $draftStock->transaction->branch->parent->code ?? $draftStock->initialInventoryBalance->branch->parent->code;


        if (!empty($latestItemCatalog)) {
            $convertInvNumberToArray = explode('.', $latestItemCatalog->code);
            $startingNumber = end($convertInvNumberToArray);
            $startValue = str_pad((int)$startingNumber + 1, 4, '0', STR_PAD_LEFT);
            return $month . '.' . $year . '.' . $code . '-' . $branchCode . '.' . $startValue;
        }

        $startingNumber = '000';
        $startValue = str_pad((int)$startingNumber + 1, 4, '0', STR_PAD_LEFT);

        return $month . '.' . $year . '.' . $code . '-' .
            $branchCode . '.' . $startValue;
    }


    public function findByItemId(ItemCollection $itemCollection): LengthAwarePaginator
    {
        $query = $this->itemCatalogRepository->findByItemId($itemCollection)->paginate(self::$perPage);
        $data = $query->getCollection()->map(function ($itemCatalog) {
            $unitType = $itemCatalog->transaction?->item?->unitType?->name ?? $itemCatalog->initialInventoryBalance?->item?->unitType?->name;
            return [
                'id' => $itemCatalog->id,
                'code' => $itemCatalog->code,
                'condition' => $itemCatalog->condition,
                'status' => $itemCatalog->status,
                'available_qty' => "$itemCatalog->available_qty $unitType",
                'broken_qty' => "$itemCatalog->broken_qty $unitType",
                'created_by' => $itemCatalog->createdBy->name,
            ];
        });


        $query->setCollection($data);
        return $query;
    }
}
