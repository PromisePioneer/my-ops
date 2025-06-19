<?php

namespace App\Http\Controllers;

use AllowDynamicProperties;
use App\Support\Inventory\StockManagement\MustReorderStock\Service\MustReorderStockService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

#[AllowDynamicProperties] class MustReorderStockController extends Controller
{
    public function __construct()
    {
        $this->mustReorderStockService = new MustReorderStockService();
    }


    public function index(Request $request): Factory|Application|View
    {
        if (!$request->user()->can('Lihat Menu Stok Yang Harus Di Order')) {
            abort(403);
        };
        return view('pages.inventory.must-reorder-stocks.index');
    }


    public function data(Request $request): JsonResponse
    {
        return response()->json($this->mustReorderStockService->data($request));
    }


    public function filter(Request $request): JsonResponse
    {
        return response()->json($this->mustReorderStockService->filter($request));
    }


    public function search(Request $request): JsonResponse
    {
        return response()->json($this->mustReorderStockService->search($request));
    }
}
