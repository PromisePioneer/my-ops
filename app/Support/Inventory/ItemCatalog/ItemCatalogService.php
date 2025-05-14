<?php

namespace App\Support\Inventory\ItemCatalog;

use AllowDynamicProperties;
use App\Http\Requests\ItemCatalogRequest;
use App\Models\DraftStock;
use App\Models\ItemCatalog;
use App\Models\Stock;
use App\Models\StockWithdrawal;
use App\Models\StockWithdrawalItem;
use App\Support\Inventory\DraftStock\Repository\ItemCatalogRepository;
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
            $stock = Stock::where('transaction_id', $draftStock->transaction_id)->orWhere('initial_balance_inventory_id', $draftStock->initial_balance_inventory_id)
                ->where('condition', $request->input('condition'))
                ->first();


            $draftStock->decrement('qty');

            if (!$stock) {
                $newStock = Stock::create([
                    'branch_id' => $draftStock->transaction?->branch_id ?? $draftStock->initialInventoryBalance->branch_id,
                    'transaction_id' => $draftStock->transaction_id,
                    'initial_balance_inventory_id' => $draftStock->initial_balance_inventory_id,
                    'item_id' => $draftStock->transaction->item_id ?? $draftStock->initialInventoryBalance->item_id,
                    'qty' => 1,
                    'condition' => $request->condition
                ]);

                ItemCatalog::create([
                    'transaction_id' => $draftStock->transaction_id,
                    'initial_balance_inventory_id' => $draftStock->initial_balance_inventory_id,
                    'stock_id' => $newStock->id,
                    'code' => $request->code,
                    'condition' => $request->condition,
                    'created_by' => $request->user()->id,
                ]);
            } else {
                $stock->increment('qty');
                ItemCatalog::create([
                    'transaction_id' => $draftStock->transaction_id,
                    'initial_balance_inventory_id' => $draftStock->initial_balance_inventory_id,
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

        $oldStock = Stock::where('transaction_id', $itemCatalog->transaction_id)
            ->where('branch_id', $itemCatalog->transaction->branch_id)
            ->where('condition', $itemCatalog->condition)
            ->lockForUpdate()
            ->first();


        DB::transaction(function () use ($oldStock, $itemCatalog) {
            if (!empty($oldStock)) {
                $draftStock = DraftStock::whereHas('transaction', function ($query) use ($itemCatalog) {
                    $query->where('id', $itemCatalog->transaction_id)
                        ->where('branch_id', $itemCatalog->transaction->branch_id);
                })->increment('qty');


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
