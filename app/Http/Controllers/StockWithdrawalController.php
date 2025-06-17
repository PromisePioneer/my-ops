<?php

namespace App\Http\Controllers;

use AllowDynamicProperties;
use App\Http\Requests\ReturnedItemRequest;
use App\Http\Requests\StockWithdrawalRequest;
use App\Models\ItemCatalog;
use App\Models\ReturnedItem;
use App\Models\Stock;
use App\Models\StockWithdrawal;
use App\Models\StockWithdrawalItem;
use App\Support\HelperService\HandleFileUploadService;
use App\Support\Inventory\StockWithdrawal\Service\StockWithdrawalService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;
use Throwable;

#[AllowDynamicProperties] class StockWithdrawalController extends Controller
{


    public function __construct()
    {
        $this->stockWithdrawalService = new StockWithdrawalService();
        $this->handleUploadService = new HandleFileUploadService();
    }


    public function index(): View
    {
        return view('pages.inventory.stock-withdrawals.index');
    }


    public function data(): JsonResponse
    {
        return response()->json($this->stockWithdrawalService->data());
    }


    public function show(StockWithdrawal $stockWithdrawal): JsonResponse
    {
        $stockWithdrawal->load('stockWithdrawalItems', 'stockWithdrawalByEmployees', 'stockWithdrawalItems.stock.transaction.item', 'stockWithdrawalByEmployees.user.roles', 'pic.roles', 'stocker.roles');

        $stockWithdrawalItem = $stockWithdrawal->stockWithdrawalItems->map(function ($item) {
            return [
                'item_name' => $item->stock->transaction?->item?->name ?? $item->stock->initialInventoryBalance?->item->name,
                'code' => $item->code,
                'qty' => $item->qty,
                'status' => $item->status,
            ];
        });


        $stockWithdrawalByEmployee = $stockWithdrawal->stockWithdrawalByEmployees->map(function ($item) {
            return [
                'name' => $item->user->name,
                'roles' => $item->user->roles->pluck('name')->implode(', '),
                'nik' => $item->user->nip,
                'profile_pic' => $item->user->profile_pic,
            ];
        });


        return response()->json([
            'stock_withdrawal' => $stockWithdrawal,
            'stock_withdrawal_items' => $stockWithdrawalItem,
            'stock_withdrawal_by_employee' => $stockWithdrawalByEmployee,
        ]);
    }


    public function search(Request $request)
    {

    }

    public function filter()
    {

    }


    public function create(): View
    {
        return view('pages.inventory.stock-withdrawals.create');
    }


    public function sessionStore(Request $request): void
    {
        $stockWithdrawal = [
            'code' => $request->get('code'),
            'qty' => $request->get('qty'),
            'stock_id' => $request->get('stock_id'),
            'item_id' => $request->get('item_id'),
            'item_name' => $request->get('item_name'),
        ];
        session()->push('stock_withdrawal_item', $stockWithdrawal);
    }



    /**
     * @throws Throwable
     */
    public function store(StockWithdrawalRequest $request): JsonResponse
    {
        $this->stockWithdrawalService->store($request);
        return response()->json([
            'message' => 'Data berhasil disimpan'
        ]);
    }


    /**
     * @throws Throwable
     */
    public function destroy(StockWithdrawal $stockWithdrawal): void
    {
        DB::transaction(function () use ($stockWithdrawal) {
            foreach ($stockWithdrawal->stockWithdrawalItems as $item) {
                if ($item->status === 'Dikembalikan' || $item->status === 'Terpakai') {
                    throw new Exception('Data tidak dapat dihapus, dikarenakan barang sudah ada yg di kembalikan atau terpakai', 403);
                }
                ItemCatalog::where('code', $item->code)->update(['status' => 'Tersedia']);
                $stock = Stock::where('id', $item->stock_id)->first();
                $stock->increment('qty', $item->qty);
                $stock->decrement('on_hold_qty', $item->qty);
            }
            $stockWithdrawal->delete();
        });
    }


    public function confirmedByPIC(StockWithdrawal $stockWithdrawal): JsonResponse
    {
        $this->stockWithdrawalService->confirmedByPIC($stockWithdrawal);

        return response()->json([
            'message' => 'data berhasil dikonfirmasi'
        ]);
    }


    public function confirmedByStocker(StockWithdrawal $stockWithdrawal): JsonResponse
    {
        $this->stockWithdrawalService->confirmedByStocker($stockWithdrawal);
        return response()->json([
            'message' => 'data berhasil dikonfirmasi'
        ]);
    }


    public function return(StockWithdrawal $stockWithdrawal): View
    {
        return view('pages.inventory.stock-withdrawals.returned-item.index', compact('stockWithdrawal'));
    }


    public function getStockWithdrawalItems(StockWithdrawal $stockWithdrawal): JsonResponse
    {
        return response()->json($this->stockWithdrawalService->getStockWithdrawalItems($stockWithdrawal));
    }

    public function getStockWithdrawalItem(StockWithdrawalItem $stockWithdrawalItem): JsonResponse
    {
        $itemCatalog = ItemCatalog::with('stock.transaction.item', 'stock.initialInventoryBalance.item')
            ->where('code', $stockWithdrawalItem->code)
            ->first();
        return response()->json([
            'withdrawal_item' => $stockWithdrawalItem,
            'item_catalog' => $itemCatalog
        ]);
    }


    /**
     * @throws Throwable
     */
    public function returningItems(ReturnedItemRequest $request, StockWithdrawalItem $stockWithdrawalItem): void
    {
        $stockWithdrawalItem->load('stock');
        DB::transaction(function () use ($request, $stockWithdrawalItem) {
            $itemCatalog = ItemCatalog::with('stock.transaction.item')
                ->where('code', $stockWithdrawalItem->code)
                ->first();

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
        if ($itemCatalog->stock->transaction->item->unitType->name === 'Meter'
            && $itemCatalog->stock->transaction->item->category->name === 'Kategori 1') {
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
        if ($itemCatalog->stock->transaction->item->unitType->name !== 'Meter'
            && $itemCatalog->stock->transaction->item->category->name === 'Kategori 3') {
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


    public function getSessions(): JsonResponse
    {
        $stock = session()->get('stock_withdrawal_item') ?? [];
        return response()->json($stock);
    }


    public function flushSessions(): void
    {
        session()->forget('stock_withdrawal_item');
    }


    public function deleteSessions(Request $request): void
    {
        Session::forget("stock_withdrawal_item.$request->index");
    }

    private function ifNotMeterAndNotCategory3Store(?ItemCatalog $itemCatalog, StockWithdrawalItem $stockWithdrawalItem, ReturnedItemRequest $request): void
    {
        if ($itemCatalog->stock->transaction->item->unitType->name !== 'Meter'
            && $itemCatalog->stock->transaction->item->category->name !== 'Kategori 3') {
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
