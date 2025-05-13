<?php

namespace App\Support\Inventory\Stock;

use AllowDynamicProperties;
use App\Http\Requests\ItemCatalogRequest;
use App\Models\DraftStock;
use App\Models\ItemCatalog;
use App\Models\Stock;
use App\Models\StockWithdrawal;
use App\Models\StockWithdrawalItem;
use App\Support\Inventory\Stock\DraftStock\Repository\ItemCatalogRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Throwable;

#[AllowDynamicProperties] class ItemCatalogService
{

    private static int $perPage = 10;

    public function __construct()
    {
        $this->itemCatalogRepository = new ItemCatalogRepository();
    }

    public function findByTransactionIdOrInitialBalanceInventoryId(DraftStock $draftStock): LengthAwarePaginator
    {
        $catalog = $this->itemCatalogRepository->findByTransactionIdOrInitialBalanceInventoryId($draftStock)->paginate(self::$perPage);
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
    public function storeByDraftStockId(ItemCatalogRequest $request, DraftStock $draftStock): void
    {
        DB::transaction(function () use ($request, $draftStock) {
            $draftStock->load('transaction');
            $stock = Stock::where('draft_stock_id', $draftStock->id)
                ->where('condition', $request->input('condition'))
                ->first();


            $draftStock->decrement('qty');

            if (!$stock) {

                $newStock = Stock::create([
                    'branch_id' => $draftStock->transaction?->branch_id ?? $draftStock->initialInventoryBalance->branch_id,
                    'transaction_id' => $draftStock->transaction_id,
                    'initial_balance_inventory_id' => $draftStock->initial_balance_inventory_id,
                    'item_id' => $draftStock->transaction->item_id ?? $draftStock->initialInventoryBalance->item_id,
                    'draft_stock_id' => $draftStock->id,
                    'qty' => 1,
                    'condition' => $request->condition
                ]);

                ItemCatalog::create([
                    'transaction_id' => $draftStock->transaction_id,
                    'initial_balance_inventory_id' => $draftStock->initial_balance_inventory_id,
                    'draft_stock_id' => $draftStock->id,
                    'stock_id' => $newStock->id,
                    'item_id' => $draftStock->transaction->item_id ?? $draftStock->initialInventoryBalance->item_id,
                    'code' => $request->code,
                    'condition' => $request->condition,
                    'created_by' => $request->user()->id,
                ]);
            } else {
                $stock->increment('qty');
                ItemCatalog::create([
                    'transaction_id' => $draftStock->transaction_id,
                    'initial_balance_inventory_id' => $draftStock->initial_balance_inventory_id,
                    'item_id' => $draftStock->transaction->item_id ?? $draftStock->initialInventoryBalance->item_id,
                    'draft_stock_id' => $draftStock->id,
                    'stock_id' => $stock->id,
                    'code' => $request->code,
                    'condition' => $request->condition,
                    'created_by' => $request->user()->id,
                ]);
            }
        });
    }


    /**
     * @throws Throwable
     */
    public function destroy(ItemCatalog $itemCatalog): void
    {
        $itemCatalog->load('transaction');

        $oldStock = Stock::where('draft_stock_id', $itemCatalog->draft_stock_id)
            ->where('condition', $itemCatalog->condition)
            ->lockForUpdate()
            ->first();


        DB::transaction(function () use ($oldStock, $itemCatalog) {
            if (!empty($oldStock)) {
                DraftStock::where('id', $itemCatalog->draft_stock_id)->increment('qty');
                $oldStock->decrement('qty');
                $itemCatalog->delete();
            }
        });
    }


    public function getSelectedItemCatalogByStockWithdrawalId(StockWithdrawal $stockWithdrawal)
    {
        $data = StockWithdrawalItem::with('stock', 'stock.transaction.item')->whereHas('stock.transaction.item.category', function ($query) {
            $query->where('name', '!=', 'Kategori 4');
        })->where('stock_withdrawal_id', $stockWithdrawal->id)->get();

        return $data->map(function ($query) {

            return [
                'id' => $query->id,
                'code' => "SN: {$query->code}"
            ];
        });
    }
}
