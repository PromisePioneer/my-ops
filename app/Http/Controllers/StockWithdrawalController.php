<?php

namespace App\Http\Controllers;

use AllowDynamicProperties;
use App\Support\Inventory\Stock\StockWithdrawal\Service\StockWithdrawalService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

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


    public function store(Request $request): JsonResponse
    {
        $this->stockWithdrawalService->store($request);
        return response()->json([
            'message' => 'Data berhasil disimpan'
        ]);
    }


    public function edit()
    {

    }


    public function confirm()
    {

    }


    public function destroy()
    {

    }
}
