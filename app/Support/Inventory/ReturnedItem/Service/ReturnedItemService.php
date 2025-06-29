<?php

namespace App\Support\Inventory\ReturnedItem\Service;

use AllowDynamicProperties;
use App\Http\Requests\ReturnedItemRequest;
use App\Models\ItemCatalog;
use App\Models\ReturnedItem;
use App\Models\Stock;
use App\Models\StockWithdrawal;
use App\Models\StockWithdrawalItem;
use App\Support\HelperService\HandleFileUploadService;
use App\Support\Inventory\ReturnedItem\Repository\ReturnedItemRepository;
use App\Support\Master\Accounting\Assets\Service\AssetService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

#[AllowDynamicProperties] class ReturnedItemService
{

    private static int $perPage = 10;

    public function __construct()
    {
        $this->returnedItemRepository = new ReturnedItemRepository();
        $this->assetService = new AssetService();
        $this->handleUploadService = new HandleFileUploadService();
    }

    public function getReturnedItemByStockWithdrawalId(StockWithdrawal $stockWithdrawal): LengthAwarePaginator
    {
        $returnedItems = $this->returnedItemRepository->getReturnedItemByStockWithdrawalId($stockWithdrawal)->paginate(self::$perPage);
        return self::formattedData($returnedItems);
    }


    private static function formattedData(LengthAwarePaginator $returnedItems): LengthAwarePaginator
    {
        $data = $returnedItems->getCollection()->map(function ($returnedItem) {
            $consumedQty = 0;

            if ($returnedItem->stockWithdrawalItem->qty) {
                $consumedQty += $returnedItem->stockWithdrawalItem?->qty - $returnedItem->remaining_qty - $returnedItem->broken_qty;
            }


            if ($consumedQty < 0) {
                $consumedQty = 0;
            }


            $item = $returnedItem->stockWithdrawalItem->stock->transaction?->item ?? $returnedItem->stockWithdrawalItem->stock->initialInventoryBalance->item;

            return [
                'id' => $returnedItem->id,
                'code' => $returnedItem->stockWithdrawalItem->code,
                'item_name' => $item->name,
                'qty' => $returnedItem->stockWithdrawalItem->qty . $item->unitType->name,
                'consumed_qty' => $consumedQty,
                'returned_qty' => $returnedItem->remaining_qty ?? 0,
                'broken_qty' => $returnedItem->broken_qty,
                'status' => $returnedItem->status,
                'attachment' => $returnedItem->attachment,
            ];
        });

        $returnedItems->setCollection($data);
        return $returnedItems;
    }

    public function store(ReturnedItemRequest $request, StockWithdrawalItem $stockWithdrawalItem): void
    {
        $stockWithdrawalItem->load('stock', 'stockWithdrawal');
        DB::transaction(function () use ($request, $stockWithdrawalItem) {
            $itemCatalog = ItemCatalog::with('stock.transaction.item')
                ->where('code', $stockWithdrawalItem->code)
                ->first();


            if (!empty($itemCatalog->asset_id) && empty($itemCatalog->asset->depreciation)) {
                $this->assetService->confirm($itemCatalog->asset, $stockWithdrawalItem->stockWithdrawal->date);
            }

            $this->category4Store($stockWithdrawalItem, $request);

            if (!empty($stockWithdrawalItem->code)) {
                $this->category1Store($itemCatalog, $stockWithdrawalItem, $request);
                $this->ifCategory3Store($itemCatalog, $stockWithdrawalItem, $request);
                $this->ifNotMeterAndNotCategory3Store($itemCatalog, $stockWithdrawalItem, $request);


                $itemCatalog->update(['status' => $request->status === 'Terpakai' ? 'Terpakai' : 'Tersedia']);
            }
        });
    }


    public function category1Store(ItemCatalog $itemCatalog, StockWithdrawalItem $stockWithdrawalItem, ReturnedItemRequest $request): void
    {
        $item = $itemCatalog->stock->transaction?->item ?? $itemCatalog->stock->initialInventoryBalance->item;
        if ($item->unitType->name === 'Meter' && $item->category->name === 'Kategori 1') {
            ReturnedItem::create([
                'stock_withdrawal_item_id' => $stockWithdrawalItem->id,
                'status' => $request->status,
                'remaining_qty' => $request->remaining_qty,
                'item_condition' => $request->item_condition,
                'broken_qty' => $request->broken_qty,
                'attachment' => $this->handleUploadService->upload(
                    $request,
                    'documents/returned-items/attachment/',
                    'attachment',
                ),
            ]);
            if ($request->status === 'Habis') {
                $itemCatalog->stock->decrement('on_hold_qty', $stockWithdrawalItem->qty);
            }


            if ($request->status === 'Sisa') {
                $itemCatalog->stock->decrement('on_hold_qty', $stockWithdrawalItem->qty);
                $itemCatalog->increment('available_qty', $request->remaining_qty - $request->broken_qty);
                $itemCatalog->stock->increment('available_qty', $request->remaining_qty - $request->broken_qty);
                if ($request->item_condition === 'Rusak') {
                    $itemCatalog->stock->increment('broken_qty', $request->broken_qty);
                    $itemCatalog->increment('broken_qty', $request->broken_qty);
                }
            }


            $itemCatalog->update(['status' => 'Tersedia']);
        }
    }

    public function ifCategory3Store(
        ItemCatalog         $itemCatalog,
        StockWithdrawalItem $stockWithdrawalItem,
        ReturnedItemRequest $request
    ): void
    {
        $item = $itemCatalog->stock->transaction?->item ?? $itemCatalog->stock->initialInventoryBalance->item;
        if ($item->unitType->name !== 'Meter' && $item->category->name === 'Kategori 3') {
            ReturnedItem::create([
                'stock_withdrawal_item_id' => $stockWithdrawalItem->id,
                'status' => $request->status,
                'remaining_qty' => 1,
                'item_condition' => $request->item_condition ?? 'Baik',
                'broken_qty' => $request->item_condition === 'Rusak' ? 1 : 0,
                'attachment' => $this->handleUploadService->upload(
                    $request,
                    'documents/returned-items/attachment/',
                    'attachment',
                ),
            ]);

            $itemCatalog->increment('available_qty');
            $itemCatalog->stock->decrement('on_hold_qty');
            $itemCatalog->stock->increment('available_qty');
            $itemCatalog->update(['status' => 'Tersedia']);

        }
    }

    public function category4Store($stockWithdrawalItem, $request): void
    {
        if (empty($stockWithdrawalItem->code)) {
            $stock = Stock::where('id', $stockWithdrawalItem->stock_id)->first();
            ReturnedItem::create([
                'stock_withdrawal_item_id' => $stockWithdrawalItem->id,
                'status' => $request->status,
                'remaining_qty' => $request->remaining_qty,
                'item_condition' => $request->item_condition,
                'broken_qty' => $request->broken_qty,
                'attachment' => $this->handleUploadService->upload(
                    $request,
                    'documents/returned-items/attachment/',
                    'attachment',
                ),
            ]);


            if ($request->status === 'Habis') {
                $stock->decrement('on_hold_qty', $stockWithdrawalItem->qty);
            }

            if ($request->status === 'Sisa') {
                $stock->decrement('on_hold_qty', $stockWithdrawalItem->qty);
                $stock->increment('available_qty', $request->remaining_qty - $request->broken_qty);

                if ($request->item_condition === 'Rusak') {
                    $stock->increment('broken_qty', $request->broken_qty);
                }
            }
        }
    }

    private function ifNotMeterAndNotCategory3Store(?ItemCatalog $itemCatalog, StockWithdrawalItem $stockWithdrawalItem, ReturnedItemRequest $request): void
    {
        $item = $itemCatalog->stock->transaction?->item ?? $itemCatalog->stock->initialInventoryBalance->item;
        if ($item->unitType->name !== 'Meter' && $item->category->name !== 'Kategori 3') {
            ReturnedItem::create([
                'stock_withdrawal_item_id' => $stockWithdrawalItem->id,
                'status' => $request->status,
                'remaining_qty' => 1,
                'item_condition' => $request->item_condition ?? 'Baik',
                'broken_qty' => $request->item_condition === 'Rusak' ? 1 : 0,
                'attachment' => $this->handleUploadService->upload(
                    $request,
                    'documents/returned-items/attachment/',
                    'attachment',
                ),
            ]);

            if ($request->status === 'Terpakai') {
                $itemCatalog->stock->decrement('on_hold_qty');
                $itemCatalog->update(['status' => 'Terpakai']);
            }


            if ($request->status === 'Dikembalikan' && $request->item_condition === 'Baik') {
                $itemCatalog->stock->increment('available_qty');
                $itemCatalog->stock->decrement('on_hold_qty');
                $itemCatalog->increment('available_qty');
                $itemCatalog->update(['status' => 'Tersedia']);
            }


            if ($request->status === 'Dikembalikan' && $request->item_condition === 'Rusak') {
                $itemCatalog->stock->decrement('on_hold_qty');
                $itemCatalog->increment('broken_qty');
                $itemCatalog->stock->increment('broken_qty');
                $itemCatalog->update([
                    'status' => 'Tersedia',
                    'condition' => 'Rusak',
                ]);
            }
        }
    }

}
