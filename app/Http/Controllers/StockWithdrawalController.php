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
use App\Support\Inventory\StockWithdrawal\Service\StockWithdrawalService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Throwable;

#[AllowDynamicProperties] class StockWithdrawalController extends Controller
{

    public function __construct()
    {
        $this->stockWithdrawalService = new StockWithdrawalService();
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
        $stockWithdrawal->load('stockWithdrawalItems', 'stockWithdrawalByEmployees', 'stockWithdrawalItems.stock.item', 'stockWithdrawalByEmployees.user.roles', 'pic.roles', 'stocker.roles');

        $stockWithdrawalItem = $stockWithdrawal->stockWithdrawalItems->map(function ($item) {
            return [
                'item_name' => $item->stock->item->name,
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
        return view('pages.inventory.stock-withdrawals.returned-stock-form', compact('stockWithdrawal'));
    }


    public function getStockWithdrawalItems(StockWithdrawal $stockWithdrawal): JsonResponse
    {
        return response()->json($this->stockWithdrawalService->getStockWithdrawalItems($stockWithdrawal));
    }

    public function getStockWithdrawalItem(StockWithdrawalItem $stockWithdrawalItem): JsonResponse
    {
        $itemCatalog = ItemCatalog::where('code', $stockWithdrawalItem->code)->first();
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
            $itemCatalog = ItemCatalog::with('stock.item')->where('code', $stockWithdrawalItem->code)->first();

            if ($itemCatalog->stock->item->must_have_code === 1) {
                ReturnedItem::create([
                    'stock_withdrawal_item_id' => $stockWithdrawalItem->id,
                    'status' => $request->status,
                    'remaining_qty' => $request->status === 'Habis' ? 0 : $request->remaining_qty,
                    'item_condition' => $request->status === 'Habis' ? 'Habis' : $request->item_condition,
                    'broken_qty' => $request->status === 'Habis' ? 0 : $request->broken_qty,
                ]);

                $stockWithdrawalItem->update([
                    'status' => 'Habis'
                ]);

                $itemCatalog->update([
                    'status' => 'Tersedia',
                    'qty_in_meter' => $request->remaining_qty - $request->broken_qty,
                ]);

                if ($request->status !== 'Habis') {
                    $itemCatalog->increment('broken_qty', $request->broken_qty);
                }
                $itemCatalog->stock->increment('qty');
                $itemCatalog->stock->decrement('on_hold_qty');
            }
        });
    }
}
