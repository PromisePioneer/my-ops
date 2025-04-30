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
        $draftStock->load('branch', 'transaction', 'transaction.item');
        return response()->json($draftStock);
    }

    public function detail(DraftStock $draftStock): View
    {
        return view('pages.inventory.goods.stocks.draft-stocks.detail', compact('draftStock'));
    }


    public function generateCodeIfCodeNotListedOnItem(DraftStock $draftStock): string
    {
        $draftStock->load('branch.parent', 'item');
        $latestItemCatalog = ItemCatalog::with('draftStock.branch.parent', 'item')
            ->where('transaction_id', $draftStock->transaction_id)
            ->latest()
            ->first();

        $month = date('m');
        $year = date('y');

        if ($latestItemCatalog) {
            $convertInvNumberToArray = explode('.', $latestItemCatalog->code);
            $startingNumber = end($convertInvNumberToArray);
            $startValue = str_pad((int)$startingNumber + 1, 3, '0', STR_PAD_LEFT);

            return $month . '.' . $year . '.' . $latestItemCatalog->item->code . '-' . $latestItemCatalog->draftStock->branch->parent->code . '.' . $startValue;
        }

        $startingNumber = '000';
        $startValue = str_pad((int)$startingNumber + 1, 3, '0', STR_PAD_LEFT);

        return $month . '.' . $year . '.' . $draftStock->item->code . '-' . $draftStock->branch?->parent?->code . '.' . $startValue;
    }

}
