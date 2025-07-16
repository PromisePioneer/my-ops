<?php

namespace App\Http\Controllers\Transaction;

use AllowDynamicProperties;
use App\Http\Controllers\Controller;
use App\Http\Requests\InitialInventoryBalanceRequest;
use App\Models\Transaction;
use App\Support\HelperService\FinancialClosePeriodService;
use App\Support\Master\Operational\InitialInventoryBalance\Service\InitialInventoryBalanceService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Throwable;

#[AllowDynamicProperties] class InitialInventoryBalanceController extends Controller
{
    public function __construct()
    {
        $this->initialInventoryBalanceService = new InitialInventoryBalanceService();
        $this->financialClosePeriodService = new FinancialClosePeriodService();
    }


    /**
     * @throws AuthorizationException
     */
    public function index(): View
    {
        $startDate = $this->financialClosePeriodService->startDate()->format('d/m/Y');
        $endDate = $this->financialClosePeriodService->endDate()->format('d/m/Y');


        $this->authorize('viewInitialInventoryBalance', Transaction::class);
        return view('pages.master.accounting.initial-inventory-balances.index', compact(
            'startDate', 'endDate'
        ));
    }


    /**
     * @throws AuthorizationException
     */
    public function data(Request $request): JsonResponse
    {
        $this->authorize('viewInitialInventoryBalance', Transaction::class);
        return response()->json($this->initialInventoryBalanceService->data($request));
    }


    /**
     * @throws AuthorizationException
     */
    public function search(Request $request): JsonResponse
    {
        $this->authorize('viewInitialInventoryBalance', Transaction::class);
        return response()->json($this->initialInventoryBalanceService->search($request));
    }


    /**
     * @throws AuthorizationException
     */

    public function filter(Request $request): JsonResponse
    {
        $this->authorize('viewInitialInventoryBalance', Transaction::class);
        $this->authorize('filterByBranch', Transaction::class);
        return response()->json($this->initialInventoryBalanceService->filter($request));
    }


    /**
     * @throws AuthorizationException
     */

    public function create(): View
    {
        $this->authorize('createInitialInventoryBalance', Transaction::class);
        return view('pages.master.accounting.initial-inventory-balances.form');
    }

    /**
     * @throws AuthorizationException
     */
    public function store(InitialInventoryBalanceRequest $request): JsonResponse
    {
        $this->authorize('createInitialInventoryBalance', Transaction::class);
        $this->initialInventoryBalanceService->store($request);
        return response()->json(['message' => 'Data berhasil disimpan']);
    }


    /**
     * @throws AuthorizationException
     */
    public function edit(Transaction $transaction): View
    {
        $this->authorize('updateInitialInventoryBalance', Transaction::class);
        $transaction->load('branch', 'supplier', 'item', 'stockAccount', 'branch.parent');
        return view('pages.master.accounting.initial-inventory-balances.form', compact('transaction'));
    }

    /**
     * @throws AuthorizationException
     */
    public function update(InitialInventoryBalanceRequest $request, Transaction $transaction): JsonResponse
    {
        $this->authorize('updateInitialInventoryBalance', $transaction);
        $this->initialInventoryBalanceService->update($request, $transaction);
        return response()->json(['message' => 'Data berhasil disimpan']);
    }

    /**
     * @throws Throwable
     * @throws AuthorizationException
     */
    public function confirm(Request $request, Transaction $transaction): JsonResponse
    {
        $this->authorize('confirmInitialInventoryBalance', $transaction);
        $this->initialInventoryBalanceService->confirm($request, $transaction);
        return response()->json([
            'message' => 'data berhasil dihapus'
        ]);
    }


    /**
     * @throws Throwable
     * @throws AuthorizationException
     */
    public function destroy(Request $request, Transaction $transaction): JsonResponse
    {
        $this->authorize('delete', $transaction);
        DB::transaction(function () use ($request, $transaction) {
            $transaction->whereIn('id', $request->get('id'))->delete();
        });
        return response()->json([
            'message' => 'data berhasil dihapus'
        ]);
    }
}
