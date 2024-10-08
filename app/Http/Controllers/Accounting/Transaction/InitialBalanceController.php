<?php

namespace App\Http\Controllers\Accounting\Transaction;

use App\Http\Controllers\Controller;
use App\Models\AccountTransaction;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;

class InitialBalanceController extends Controller
{


    public function index(): View
    {
        return view('pages.account-master.initial-balances.index');
    }


    public function data(): JsonResponse
    {
        $data = AccountTransaction::where('type', 'SA')->orderBy('date')->paginate(10);
        return response()->json($data);
    }


    public function search()
    {
    }

    public function getAccountData()
    {
    }

    public function store()
    {
    }


    public function edit()
    {
    }


    public function update()
    {
    }


    public function destroy()
    {
    }

}
