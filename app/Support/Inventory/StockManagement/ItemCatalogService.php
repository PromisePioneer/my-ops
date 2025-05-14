<?php

namespace App\Support\Inventory\StockManagement;

use AllowDynamicProperties;
use App\Http\Requests\ItemCatalogRequest;
use App\Models\DraftStock;
use App\Models\ItemCatalog;
use App\Models\Stock;
use App\Support\Inventory\StockManagement\DraftStock\Repository\ItemCatalogRepository;
use App\Support\Inventory\StockManagement\Stock\Repository\StockRepository;
use App\Support\Inventory\StockManagement\StockWithdrawal\Repository\StockWithdrawalItemRepository;
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
        $draftStock->load('transaction');
        DB::transaction(function () use ($request, $draftStock) {
            $stock = $this->stockRepository->findByDraftStock($draftStock, $request);
            $draftStock->decrement('qty');
            if (empty($stock)) {
                $newStock = $this->stockStore($draftStock, $request);
                $this->itemCatalogStore($request, $draftStock, $newStock);
            } else {
                $stock->increment('qty');
                $this->itemCatalogStore($request, $draftStock, null, $stock);
            }
        });
    }


    public function stockStore(DraftStock $draftStock, Request $request)
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


    public function itemCatalogStore($request, $draftStock, $newStock = null, $stock = null): void
    {
        ItemCatalog::create([
            'transaction_id' => $draftStock->transaction_id,
            'initial_balance_inventory_id' => $draftStock->initial_balance_inventory_id,
            'draft_stock_id' => $draftStock->id,
            'stock_id' => $stock->id ?? $newStock->id,
            'item_id' => $draftStock->transaction->item_id ?? $draftStock->initialInventoryBalance->item_id,
            'code' => $request->code,
            'condition' => $request->condition,
            'created_by' => $request->user()->id,
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
            }
        });
    }

    public function generateAutomaticItemCode(DraftStock $draftStock): string
    {
        $draftStock->load('transaction.branch.parent', 'transaction.item');
        $latestItemCatalog = $this->itemCatalogRepository->getLatestItem();
        $month = date('m');
        $year = date('y');


        $code = $draftStock->transaction->item->code ?? $draftStock->initialInventoryBalance->item->code;
        $branchCode = $draftStock->transaction->branch->parent->code ?? $draftStock->initialInventoryBalance->branch->parent->code;


        if ($latestItemCatalog) {
            $convertInvNumberToArray = explode('.', $latestItemCatalog->code);
            $startingNumber = end($convertInvNumberToArray);
            $startValue = str_pad((int)$startingNumber + 1, 3, '0', STR_PAD_LEFT);
            return $month . '.' . $year . '.' . $code . '-' . $branchCode . '.' . $startValue;
        }

        $startingNumber = '000';
        $startValue = str_pad((int)$startingNumber + 1, 3, '0', STR_PAD_LEFT);

        return $month . '.' . $year . '.' . $code . '-' .
            $branchCode . '.' . $startValue;
    }
}
