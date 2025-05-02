<?php

namespace App\Support\Inventory\Stock;

use AllowDynamicProperties;
use App\Http\Requests\ItemCatalogRequest;
use App\Models\DraftStock;
use App\Models\ItemCatalog;
use App\Models\Stock;
use App\Support\Inventory\Stock\DraftStock\Repository\ItemCatalogRepository;
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
    }

    public function findByDraftStockId(DraftStock $draftStock, Request $request): LengthAwarePaginator
    {
        $catalog = $this->itemCatalogRepository->getItemCatalogByDraftStockId($draftStock)->paginate(self::$perPage);
        return $this->formattedData($catalog);
    }

    public function formattedData(LengthAwarePaginator $catalog): LengthAwarePaginator
    {
        $data = $catalog->getCollection()->map(function ($query) {
            return [
                'id' => $query->id,
                'item_name' => $query->transaction->item->name,
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
            $stock = Stock::where('transaction_id', $draftStock->transaction_id)
                ->where('condition', $request->input('condition'))
                ->first();


            $draftStock->decrement('qty');

            if (!$stock) {
                $newStock = Stock::create([
                    'branch_id' => $draftStock->transaction->branch_id,
                    'transaction_id' => $draftStock->transaction_id,
                    'item_id' => $draftStock->transaction->item_id,
                    'qty' => 1,
                    'condition' => $request->condition
                ]);

                ItemCatalog::create([
                    'transaction_id' => $draftStock->transaction_id,
                    'stock_id' => $newStock->id,
                    'code' => $request->code,
                    'condition' => $request->condition,
                    'created_by' => $request->user()->id,
                ]);
            } else {
                $stock->increment('qty');
                ItemCatalog::create([
                    'transaction_id' => $draftStock->transaction_id,
                    'item_id' => $draftStock->transaction->item_id,
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
    public function update(ItemCatalogRequest $request, ItemCatalog $itemCatalog): void
    {
        $itemCatalog->load('transaction');

        $oldStock = Stock::where('transaction_id', $itemCatalog->transaction_id)
            ->where('branch_id', $itemCatalog->transaction->branch_id)
            ->where('condition', $itemCatalog->condition)
            ->lockForUpdate()
            ->first();

        $newStock = Stock::where('transaction_id', $itemCatalog->transaction_id)
            ->where('branch_id', $itemCatalog->transaction->branch_id)
            ->where('condition', $request->condition)
            ->lockForUpdate()
            ->first();
        DB::transaction(function () use ($oldStock, $newStock, $request, $itemCatalog) {
            if (!empty($newStock && $oldStock)) {
                if ($oldStock->condition !== $newStock->condition || $oldStock->condition !== $request->condition) {
                    $oldStock->decrement('qty');
                    $newStock->increment('qty');
                    $itemCatalog->update([
                        'stock_id' => $newStock->id,
                        'code' => $request->code,
                        'condition' => $request->condition,
                        'created_by' => $request->user()->id
                    ]);
                }
            } else {
                $oldStock->decrement('qty');
                $newStockIfNotExists = Stock::create([
                    'branch_id' => $itemCatalog->transaction->branch_id,
                    'transaction_id' => $itemCatalog->transaction_id,
                    'item_id' => $itemCatalog->transaction->item_id,
                    'qty' => 1,
                    'condition' => $request->condition
                ]);

                $itemCatalog->update([
                    'stock_id' => $newStockIfNotExists->id,
                    'code' => $request->code,
                    'condition' => $request->condition,
                    'created_by' => $request->user()->id
                ]);
            }


            $itemCatalog->update([
                'code' => $request->code,
                'condition' => $request->condition,
                'created_by' => $request->user()->id,
            ]);
        });
    }
}
