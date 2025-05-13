<?php

namespace App\Http\Controllers;

use AllowDynamicProperties;
use App\Http\Requests\StockWithdrawalRequest;
use App\Models\StockWithdrawal;
use App\Models\StockWithdrawalItem;
use App\Support\Inventory\StockManagement\StockWithdrawal\Service\StockWithdrawalService;
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


    public function search(Request $request): JsonResponse
    {
        return response()->json($this->stockWithdrawalService->search($request));
    }

    public function filter(Request $request): JsonResponse
    {
        return response()->json($this->stockWithdrawalService->filter($request));
    }


    public function data(): JsonResponse
    {
        return response()->json($this->stockWithdrawalService->data());
    }


    public function show(StockWithdrawal $stockWithdrawal): JsonResponse
    {
        $stockWithdrawal->load('stockWithdrawalItems', 'stockWithdrawalByEmployees', 'stockWithdrawalItems.stock.item', 'stockWithdrawalByEmployees.user.roles', 'pic.roles', 'stocker.roles');

        return response()->json([
            'stock_withdrawal' => $stockWithdrawal,
            'stock_withdrawal_items' => $this->stockWithdrawalService->showStockWithdrawalItems($stockWithdrawal),
            'stock_withdrawal_by_employee' => $this->stockWithdrawalService->showStockWithdrawalByEmployees($stockWithdrawal),
        ]);
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
        return response()->json(['message' => 'Data berhasil disimpan']);
    }


    /**
     * @throws Throwable
     */
    public function destroy(StockWithdrawal $stockWithdrawal): JsonResponse
    {
        $this->stockWithdrawalService->destroy($stockWithdrawal);
        return response()->json(['message' => 'data berhasil disimpan']);
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
        DB::transaction(function () use ($request, $stockWithdrawalItem) {
            $stockWithdrawalItem->update([
                'status' => $request->input('status'),
            ]);
        });
    }
}
