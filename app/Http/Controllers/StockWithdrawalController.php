<?php

namespace App\Http\Controllers;

use AllowDynamicProperties;
use App\Http\Requests\StockWithdrawalRequest;
use App\Models\ItemCatalog;
use App\Models\StockWithdrawal;
use App\Models\StockWithdrawalItem;
use App\Support\Inventory\Stock\StockWithdrawal\Service\StockWithdrawalService;
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
        return view('pages.inventory.goods.stocks.stock-withdrawals.index');
    }


    public function data(Request $request): JsonResponse
    {
        return response()->json($this->stockWithdrawalService->data($request));
    }


    public function show(StockWithdrawal $stockWithdrawal): JsonResponse
    {
        $stockWithdrawal->load('stockWithdrawalItem', 'stockWithdrawalByEmployee', 'stockWithdrawalItem.stock.item', 'stockWithdrawalByEmployee.user.roles');

        $stockWithdrawalItem = $stockWithdrawal->stockWithdrawalItem->map(function ($item) {
            return [
                'item_name' => $item->stock->item->name,
                'code' => $item->code,
                'qty' => $item->qty,
            ];
        });


        $stockWithdrawalByEmployee = $stockWithdrawal->stockWithdrawalByEmployee->map(function ($item) {
            return [
                'name' => $item->user->name,
                'roles' => $item->user->roles->pluck('name')->implode(', '),
                'nik' => $item->user->nip,
                'profile_pic' => $item->user->profile_pic,
            ];
        });


        return response()->json([
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
        return view('pages.inventory.goods.stocks.stock-withdrawals.create');
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


    public function confirm()
    {

    }


    public function destroy(StockWithdrawal $stockWithdrawal)
    {
        DB::transaction(function ($query) use ($stockWithdrawal) {

            $data = StockWithdrawalItem::with('stock', 'stock.itemCatalog')->where('stock_withdrawal_id', $stockWithdrawal->id);
            $withdrawalItems = [];
            foreach ($data->get() as $withdrawalItem) {
                foreach ($withdrawalItem->stock->itemCatalog as $itemCatalog) {
                    $withdrawalItems [] = $itemCatalog->where('stock_id', $withdrawalItem->stock_id)
                        ->where('code', $withdrawalItem->code)
                        ->where('status', 'Dibawa')
                        ->pluck('id')->toArray();
                }
            }
            ItemCatalog::whereIn('id', $withdrawalItems)->update([
                'status' => 'Tersedia',
            ]);
            $stockWithdrawal->delete();
        });


    }
}
