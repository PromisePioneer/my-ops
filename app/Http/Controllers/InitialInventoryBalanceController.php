<?php

namespace App\Http\Controllers;

use AllowDynamicProperties;
use App\Http\Requests\InitialInventoryBalanceRequest;
use App\Models\AccountTransaction;
use App\Models\DraftStock;
use App\Models\InitialInventoryBalance;
use App\Models\Stock;
use App\Support\Master\Operational\InitialInventoryBalance\Service\InitialInventoryBalanceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

#[AllowDynamicProperties] class InitialInventoryBalanceController extends Controller
{
    public function __construct()
    {
        $this->initialInventoryBalanceService = new InitialInventoryBalanceService();
    }

    public function index(): View
    {
        return view('pages.master.accounting.initial-inventory-balances.index');
    }

    public function data(): JsonResponse
    {
        return response()->json($this->initialInventoryBalanceService->data());
    }

    public function store(InitialInventoryBalanceRequest $request): JsonResponse
    {
        $this->initialInventoryBalanceService->store($request);
        return response()->json(['message' => 'Data berhasil disimpan']);
    }


    public function edit(InitialInventoryBalance $initialInventoryBalance): JsonResponse
    {
        $initialInventoryBalance->load('branch', 'supplier', 'item', 'stockAccount', 'branch.parent');
        return response()->json($initialInventoryBalance);
    }

    public function update(InitialInventoryBalanceRequest $request, InitialInventoryBalance $initialInventoryBalance)
    {
        $this->initialInventoryBalanceService->update($request, $initialInventoryBalance);
        return response()->json(['message' => 'Data berhasil disimpan']);
    }


    public function confirm(Request $request, InitialInventoryBalance $initialInventoryBalance)
    {

        DB::transaction(function () use ($request, $initialInventoryBalance) {
            $implodeID = implode(',', $request->get('id'));
            $explodeID = explode(',', $implodeID);

            $query = $initialInventoryBalance->whereIn('id', $explodeID);
            $query->update(['status' => true]);

            $selectedInitialInventoryBalance = $query
                ->with('branch', 'supplier', 'item', 'stockAccount', 'branch.parent')
                ->get();
            foreach ($selectedInitialInventoryBalance as $item) {

                if ($item->item->category->name !== 'Kategori 4') {
                    DraftStock::create([
                        'initial_balance_inventory_id' => $item->id,
                        'qty' => $item->qty
                    ]);
                } else {
                    Stock::create([
                        'initial_balance_inventory_id' => $item->id,
                        'branch_id' => $item->branch_id,
                        'item_id' => $item->item_id,
                        'qty' => $item->qty,
                        'condition' => 'Baik'
                    ]);
                }


                AccountTransaction::create([
                    'branch_id' => $item->branch->parent->id,
                    'initial_inventory_balance_id' => $item->id,
                    'date' => $item->date,
                    'account_id' => $item->stock_account_id,
                    'description' => $item->detail,
                    'transaction_type' => 'SA',
                    'entries_type' => 'debit',
                    'amount' => $item->total_price,
                ]);
            }

        });


        return response()->json([
            'message' => 'data berhasil dihapus'
        ]);
    }


    /**
     * @throws \Throwable
     */
    public function destroy(Request $request, InitialInventoryBalance $initialInventoryBalance): JsonResponse
    {

        DB::transaction(function () use ($request, $initialInventoryBalance) {
            $implodeID = implode(',', $request->get('id'));
            $explodeID = explode(',', $implodeID);

            $initialInventoryBalance->whereIn('id', $explodeID)->delete();
        });


        return response()->json([
            'message' => 'data berhasil dihapus'
        ]);
    }
}
