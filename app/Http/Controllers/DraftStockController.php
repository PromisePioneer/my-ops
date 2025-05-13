<?php

namespace App\Http\Controllers;

use AllowDynamicProperties;
use App\Models\DraftStock;
use App\Models\ItemCatalog;
use App\Support\Inventory\Stock\DraftStock\Service\DraftStockService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

#[AllowDynamicProperties] class DraftStockController extends Controller
{
    public function __construct()
    {
        $this->draftStockService = new DraftStockService();
    }

    public function index(): View
    {
        return view('pages.inventory.goods.stocks.draft-stocks.index');
    }

    public function getQty(): JsonResponse
    {
        return response()->json($this->draftStockService->getDraftStockQty());
    }

    public function data(Request $request): JsonResponse
    {
        return response()->json($this->draftStockService->data($request));
    }

    public function filter(Request $request): JsonResponse
    {
        return response()->json($this->draftStockService->filter($request));
    }

    public function search(Request $request)
    {

    }

    public function show(DraftStock $draftStock): JsonResponse
    {
        $draftStock->load('transaction', 'transaction.item', 'initialInventoryBalance.item');
        return response()->json($draftStock);
    }

    public function detail(DraftStock $draftStock): View
    {
        $draftStock->load('transaction', 'initialInventoryBalance');
        return view('pages.inventory.goods.stocks.draft-stocks.detail', compact('draftStock'));
    }


    public function generateCodeIfCodeNotListedOnItem(DraftStock $draftStock): string
    {
        $draftStock->load('transaction.branch.parent', 'transaction.item');
        $latestItemCatalog = ItemCatalog::with('transaction.branch.parent', 'transaction.item', 'initialInventoryBalance.branch.parent', 'initialInventoryBalance.item')
            ->orderBy('created_at', 'desc')
            ->latest()
            ->first();


        $month = date('m');
        $year = date('y');


        $code = $draftStock->transaction->item->code ?? $draftStock->initialInventoryBalance->item->code;
        $branchCode = $draftStock->transaction->branch->parent->code ?? $draftStock->initialInventoryBalance->branch->parent->code;


        if ($latestItemCatalog) {
            $convertInvNumberToArray = explode('.', $latestItemCatalog->code);
            $startingNumber = end($convertInvNumberToArray);
            $startValue = str_pad((int)$startingNumber + 1, 3, '0', STR_PAD_LEFT);
            return $month . '.' . $year . '.' . $code . '-' . $branchCode . '.' . $startValue;
        }

        $startingNumber = '000';
        $startValue = str_pad((int)$startingNumber + 1, 3, '0', STR_PAD_LEFT);

        return $month . '.' . $year . '.' . $code . '-' .
            $branchCode . '.' . $startValue;
    }

}
