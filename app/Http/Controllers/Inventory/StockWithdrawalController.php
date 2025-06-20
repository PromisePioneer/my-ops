<?php

namespace App\Http\Controllers\Inventory;

use AllowDynamicProperties;
use App\Http\Controllers\Controller;
use App\Http\Requests\StockWithdrawalRequest;
use App\Models\ItemCatalog;
use App\Models\Stock;
use App\Models\StockWithdrawal;
use App\Models\StockWithdrawalItem;
use App\Support\HelperService\HandleFileUploadService;
use App\Support\Inventory\StockWithdrawal\Service\StockWithdrawalService;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
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


    /**
     * @throws AuthorizationException
     */
    public function index(): View
    {
        $this->authorize('view', StockWithdrawal::class);
        return view('pages.inventory.stock-withdrawals.index');
    }


    /**
     * @throws AuthorizationException
     */
    public function data(Request $request): JsonResponse
    {
        $this->authorize('view', StockWithdrawal::class);
        return response()->json($this->stockWithdrawalService->data($request));
    }


    /**
     * @throws AuthorizationException
     */
    public function show(StockWithdrawal $stockWithdrawal): JsonResponse
    {
        $this->authorize('view', StockWithdrawal::class);
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


    /**
     * @throws AuthorizationException
     */
    public function search(Request $request): JsonResponse
    {
        $this->authorize('view', StockWithdrawal::class);
        return response()->json($this->stockWithdrawalService->search($request));
    }

    /**
     * @throws AuthorizationException
     */
    public function filter(Request $request): JsonResponse
    {
        $this->authorize('view', StockWithdrawal::class);
        return response()->json($this->stockWithdrawalService->filter($request));
    }


    /**
     * @throws AuthorizationException
     */
    public function create(): View
    {
        $this->authorize('create', StockWithdrawal::class);
        return view('pages.inventory.stock-withdrawals.create');
    }


    /**
     * @throws AuthorizationException
     */
    public function sessionStore(Request $request): void
    {
        $this->authorize('create', StockWithdrawal::class);
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
        $this->authorize('create', StockWithdrawal::class);
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
            $this->authorize('delete', $stockWithdrawal);
            foreach ($stockWithdrawal->stockWithdrawalItems as $item) {
                if ($item->returnedItem) {
                    throw new Exception('Data tidak dapat dihapus, dikarenakan barang sudah ada yg di kembalikan atau terpakai', 403);
                }
                $itemCatalog = ItemCatalog::where('code', $item->code)->first();
                if ($itemCatalog) {
                    $itemCatalog->update(['status' => 'Tersedia']);
                    $itemCatalog->increment('available_qty', $item->qty);
                }
                $stock = Stock::where('id', $item->stock_id)->first();
                $stock->increment('available_qty', $item->qty);
                $stock->decrement('on_hold_qty', $item->qty);
            }
            $stockWithdrawal->delete();
        });
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
}
