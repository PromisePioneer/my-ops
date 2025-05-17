<?php

namespace App\Http\Controllers;

use AllowDynamicProperties;
use App\Support\Inventory\StockManagement\Stock\Service\MustReorderStockService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;

#[AllowDynamicProperties] class MustReorderStockController extends Controller
{
    public function __construct()
    {
        $this->mustReorderStockService = new MustReorderStockService();
    }


    public function index(): Factory|Application|View
    {
        return view('pages.inventory.must-reorder-stocks.index');
    }


    public function data(): JsonResponse
    {
        return response()->json($this->mustReorderStockService->data());
    }
}
