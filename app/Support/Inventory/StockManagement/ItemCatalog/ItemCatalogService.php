<?php

namespace App\Support\Inventory\StockManagement\ItemCatalog;

use AllowDynamicProperties;
use App\Http\Requests\ItemCatalogRequest;
use App\Models\Asset;
use App\Models\DraftStock;
use App\Models\ItemCatalog;
use App\Models\Stock;
use App\Support\HelperService\UsefulLifeService;
use App\Support\Inventory\StockManagement\DraftStock\Repository\ItemCatalogRepository;
use App\Support\Inventory\StockManagement\Stock\Repository\StockRepository;
use App\Support\Inventory\StockManagement\StockWithdrawal\Repository\StockWithdrawalItemRepository;
use App\Support\Master\Accounting\Accounts\Repositories\AccountRepository;
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
    }

    public function findByDraftStock(DraftStock $draftStock): LengthAwarePaginator
    {
        $catalog = $this->itemCatalogRepository->findByDraftStock($draftStock)->paginate(self::$perPage);
        return $this->formattedData($catalog);
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
                'qty_in_meter' => $query->qty_in_meter,
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
                $asset = self::insertAsset($request, $draftStock, $stock);
                self::insertItemCatalog($request, $draftStock, $stock, $asset);
            } else {
                self::insertItemCatalog($request, $draftStock, $stock);
            }
        });
    }


    public function findOrCreateStock(DraftStock $draftStock, Request $request): Stock
    {
        $stock = $this->stockRepository->findByDraftStock($draftStock, $request->condition);

        if (empty($stock)) {
            return self::insertStock($draftStock, $request);
        }
        $stock->increment('qty');
        return $stock;
    }


    private static function insertAsset(Request $request, DraftStock $draftStock, Stock $stock): Asset
    {
        $itemObject = $draftStock->transaction?->item ?? $draftStock->initialInventoryBalance?->item;
        return Asset::create([
            'branch_id' => $draftStock->transaction?->branch_id ?? $draftStock->initialInventoryBalance?->branch_id,
            'code' => $request->code,
            'stock_id' => $stock->id,
            'item_id' => $draftStock->transaction?->item_id ?? $draftStock->initialInventoryBalance?->item_id,
            'date' => $draftStock->transaction?->date ?? $draftStock->initialInventoryBalance?->date,
            'useful_life' => UsefulLifeService::getUsefulLife(
                AccountRepository::findByTransactionOrInitialInventoryBalanceId($draftStock),
                $itemObject->non_building_group,
                $itemObject?->building_type
            ),
            'price' => $draftStock->transaction?->unit_price ?? $draftStock->initialInventoryBalance?->unit_price,
        ]);
    }


    private static function insertStock(DraftStock $draftStock, Request $request): Stock
    {
        return Stock::create([
            'branch_id' => $draftStock->transaction?->branch_id ?? $draftStock->initialInventoryBalance->branch_id,
            'transaction_id' => $draftStock->transaction_id,
            'initial_balance_inventory_id' => $draftStock->initial_balance_inventory_id,
            'item_id' => $draftStock->transaction->item_id ?? $draftStock->initialInventoryBalance->item_id,
            'draft_stock_id' => $draftStock->id,
            'qty' => 1,
            'condition' => $request->condition
        ]);
    }


    private static function insertItemCatalog(Request $request, DraftStock $draftStock, ?Stock $stock, ?Asset $asset = null): ItemCatalog
    {
        return ItemCatalog::create([
            'transaction_id' => $draftStock->transaction_id,
            'initial_balance_inventory_id' => $draftStock->initial_balance_inventory_id,
            'draft_stock_id' => $draftStock->id,
            'stock_id' => $stock->id,
            'item_id' => $draftStock->transaction->item_id ?? $draftStock->initialInventoryBalance->item_id,
            'code' => $request->code,
            'asset_id' => $asset->id,
            'condition' => $request->condition,
            'created_by' => $request->user()->id,
            'qty_in_meter' => $draftStock->transaction?->qty_in_meter ?? $draftStock->initialInventoryBalance?->qty_in_meter
        ]);
    }


    /**
     * @throws Throwable
     */
    public function destroy(ItemCatalog $itemCatalog): void
    {

        $itemCatalog->load('transaction');
        $oldStock = $this->stockRepository->findByItemCatalog($itemCatalog);
        DB::transaction(function () use ($oldStock, $itemCatalog) {
            if (!empty($oldStock) && $itemCatalog->status === 'Tersedia') {
                DraftStock::where('id', $itemCatalog->draft_stock_id)->increment('qty');
                $oldStock->decrement('qty');
                $itemCatalog->delete();
                Asset::where('id', $itemCatalog->asset_id)->delete();
            }
        });
    }

    public function generateAutomaticItemCode(DraftStock $draftStock): string
    {
        $draftStock->load('transaction.branch.parent', 'transaction.item');
        $latestItemCatalog = $this->itemCatalogRepository->getLatestItem()->latest()->first();
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
}
