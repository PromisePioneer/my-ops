<?php

namespace App\Http\Controllers;

use AllowDynamicProperties;
use App\Models\AccountTransaction;
use App\Support\AccountTransactions\AccountTransactionService;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

#[AllowDynamicProperties] class AccountTransactionController extends Controller
{
    public function __construct()
    {
        $this->accountTransaction = new AccountTransaction();
        $this->accountTransactionService = new AccountTransactionService();
    }


    public function index(AccountTransaction $accountTransaction): View
    {
        return view('pages.account-transactions.index', compact('accountTransaction'));
    }

    public function accountTransactionHistory(AccountTransaction $accountTransaction): JsonResponse
    {

        $accountTransactions = $this->accountTransactionService->getHistory($accountTransaction->account_id);
        return response()->json($accountTransactions);
    }
}
