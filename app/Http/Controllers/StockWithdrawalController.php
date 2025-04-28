<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class StockWithdrawalController extends Controller
{
    public function index(): View
    {
        return view('pages.inventory.goods.stocks.stock-withdrawals.index');
    }


    public function data(Request $request)
    {

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
