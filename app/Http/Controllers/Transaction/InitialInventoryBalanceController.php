<?php

namespace App\Http\Controllers\Transaction;

use AllowDynamicProperties;
use App\Http\Controllers\Controller;
use App\Http\Requests\InitialInventoryBalanceRequest;
use App\Models\InitialInventoryBalance;
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
    }


    /**
     * @throws AuthorizationException
     */
    public function index(): View
    {
        $this->authorize('view', InitialInventoryBalance::class);
        return view('pages.master.accounting.initial-inventory-balances.index');
    }


    /**
     * @throws AuthorizationException
     */
    public function data(): JsonResponse
    {
        $this->authorize('view', InitialInventoryBalance::class);
        return response()->json($this->initialInventoryBalanceService->data());
    }


    /**
     * @throws AuthorizationException
     */
    public function search(Request $request): JsonResponse
    {
        $this->authorize('view', InitialInventoryBalance::class);
        return response()->json($this->initialInventoryBalanceService->search($request));
    }


    /**
     * @throws AuthorizationException
     */

    public function filter(Request $request): JsonResponse
    {
        $this->authorize('view', InitialInventoryBalance::class);
        $this->authorize('filterByBranch', InitialInventoryBalance::class);
        return response()->json($this->initialInventoryBalanceService->filter($request));
    }


    /**
     * @throws AuthorizationException
     */

    public function create(): View
    {
        $this->authorize('create', InitialInventoryBalance::class);
        return view('pages.master.accounting.initial-inventory-balances.form');
    }

    /**
     * @throws AuthorizationException
     */
    public function store(InitialInventoryBalanceRequest $request): JsonResponse
    {
        $this->authorize('create', InitialInventoryBalance::class);
        $this->initialInventoryBalanceService->store($request);
        return response()->json(['message' => 'Data berhasil disimpan']);
    }


    /**
     * @throws AuthorizationException
     */
    public function edit(InitialInventoryBalance $initialInventoryBalance): View
    {
        $this->authorize('update', $initialInventoryBalance);
        $initialInventoryBalance->load('branch', 'supplier', 'item', 'stockAccount', 'branch.parent');
        return view('pages.master.accounting.initial-inventory-balances.form', compact('initialInventoryBalance'));
    }

    /**
     * @throws AuthorizationException
     */
    public function update(InitialInventoryBalanceRequest $request, InitialInventoryBalance $initialInventoryBalance): JsonResponse
    {
        $this->authorize('update', $initialInventoryBalance);
        $this->initialInventoryBalanceService->update($request, $initialInventoryBalance);
        return response()->json(['message' => 'Data berhasil disimpan']);
    }

    /**
     * @throws Throwable
     * @throws AuthorizationException
     */
    public function confirm(Request $request, InitialInventoryBalance $initialInventoryBalance): JsonResponse
    {
        $this->authorize('confirm', $initialInventoryBalance);
        $this->initialInventoryBalanceService->confirm($request, $initialInventoryBalance);
        return response()->json([
            'message' => 'data berhasil dihapus'
        ]);
    }


    /**
     * @throws Throwable
     * @throws AuthorizationException
     */
    public function destroy(Request $request, InitialInventoryBalance $initialInventoryBalance): JsonResponse
    {

        $this->authorize('delete', $initialInventoryBalance);
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
