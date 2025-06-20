<?php

namespace App\Http\Controllers\Inventory;

use AllowDynamicProperties;
use App\Http\Controllers\Controller;
use App\Http\Requests\StockWithdrawalRequest;
use App\Models\ItemCatalog;
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
        return response()->json($stockWithdrawalItem);
    }


    /**
     * @throws Throwable
     */
    public function returningItems(Request $request, StockWithdrawalItem $stockWithdrawalItem): void
    {
        $stockWithdrawalItem->load('stock');
        DB::transaction(function () use ($request, $stockWithdrawalItem) {
            if ($stockWithdrawalItem->code) {
                $stockWithdrawalItem->stock->decrement('on_hold_qty', $stockWithdrawalItem->qty);
                $itemCatalog = ItemCatalog::where('code', $stockWithdrawalItem?->code)->first();
                ItemCatalog::create([
                    'transaction_id' => $stockWithdrawalItem->stock?->transaction_id,
                    'stock_id' => $stockWithdrawalItem->stock?->id,
                    'draft_stock_id' => $stockWithdrawalItem->stock?->draft_stock_id,
                    'item_id' => $stockWithdrawalItem->stock?->item_id,
                    'code' => $itemCatalog->code,
                    'condition' => $itemCatalog->condition,
                    'created_by' => $itemCatalog->created_by,
                    'status' => $request->input('status') === 'Terpakai' ? 'Terpakai' : 'Tersedia',
                    'initial_balance_inventory_id' => $stockWithdrawalItem->stock?->initial_balance_inventory_id,
                    'asset_id' => $itemCatalog->asset_id,
                ]);
                $itemCatalog->delete();
                $stockWithdrawalItem->update([
                    'status' => $request->input('status')
                ]);
                if ($request->input('status') === 'Dikembalikan') {
                    $stockWithdrawalItem->stock->increment('qty');
                }
            } else {
                $stockWithdrawalItem->stock->decrement('on_hold_qty', $stockWithdrawalItem->qty);
                $stockWithdrawalItem->update([
                    'qty_used' => $stockWithdrawalItem->qty - $request->input('qty'),
                    'qty' => $request->input('qty'),
                ]);
                $stockWithdrawalItem->stock->increment('qty', $request->input('qty'));
                $stockWithdrawalItem->update([
                    'status' => 'Habis',
                ]);
            }
        });
    }
}
