<?php

namespace App\Http\Controllers;

use AllowDynamicProperties;
use App\Service\CentralWareHouseStockService;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

#[AllowDynamicProperties] class CentralWarehouseStockController extends Controller
{

    public function __construct()
    {
        $this->centralWarehouseStockService = new CentralWareHouseStockService();
    }

    public function index(): View
    {
        return view('pages.inventory.list-of-items.central-warehouse-stocks.index');
    }

    public function data(): JsonResponse
    {
        return response()->json($this->centralWarehouseStockService->data());
    }



}
