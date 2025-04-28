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
                'item_name' => $query->item->name,
                'code' => $query->code,
                'condition' => $query->condition,
                'created_at' => $query->created_at,
                'created_by' => $query->createdBy->name
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
            $stock = Stock::where('item_id', $draftStock->item_id)
                ->where('branch_id', $draftStock->branch_id)
                ->where('condition', $request->input('condition'))
                ->first();

            $itemCatalog = ItemCatalog::create([
                'item_id' => $draftStock->item_id,
                'draft_stock_id' => $draftStock->id,
                'code' => $request->code,
                'condition' => $request->condition,
                'created_by' => $request->user()->id,
            ]);

            $draftStock->decrement('qty');


            if ($stock) {
                $stock->increment('qty');
            } else {
                Stock::create([
                    'branch_id' => $draftStock->branch_id,
                    'transaction_id' => $draftStock->transaction_id,
                    'item_catalog_id' => $itemCatalog->id,
                    'item_id' => $draftStock->item_id,
                    'qty' => 1,
                    'condition' => $request->condition
                ]);
            }
        });
    }


    /**
     * @throws Throwable
     */
    public function update(ItemCatalogRequest $request, ItemCatalog $itemCatalog): void
    {
        $oldStock = Stock::where('item_catalog_id', $itemCatalog->id)
            ->where('condition', $itemCatalog->condition)
            ->first();

        $newStock = Stock::where('item_catalog_id', $itemCatalog->id)
            ->where('condition', $request->condition)
            ->first();


        DB::transaction(function () use ($oldStock, $newStock, $request, $itemCatalog) {

            if ($oldStock?->condition !== $newStock?->condition || $oldStock?->condition !== $request->condition) {
                $oldStock->decrement('qty');
                if (!empty($newStock)) {
                    $newStock->increment('qty');
                } else {
                    Stock::create([
                        'branch_id' => $itemCatalog->draftStock->branch_id,
                        'transaction_id' => $itemCatalog->draftStock->transaction->id,
                        'item_catalog_id' => $itemCatalog->id,
                        'item_id' => $itemCatalog->item_id,
                        'qty' => 1,
                        'condition' => $request->condition
                    ]);
                }
            }


            $itemCatalog->update([
                'code' => $request->code,
                'condition' => $request->condition,
                'created_by' => $request->user()->id,
            ]);
        });
    }
}
