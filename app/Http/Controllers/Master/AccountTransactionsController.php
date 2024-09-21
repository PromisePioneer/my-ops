<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\AccountTransaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AccountTransactionsController extends Controller
{
    public int $perPage = 10;
    private AccountTransaction $accountTransaction;

    public function __construct()
    {
        $this->accountTransaction = new AccountTransaction();
    }

    public function index(): View
    {
        return view('pages.transaction.account-transaction.index');
    }

    public function data(): JsonResponse
    {
        return response()->json($this->accountTransaction->getAccountTransactionBasedOnUserBranch());
    }

    public function search(Request $request): JsonResponse
    {
        $accountTransaction = $this->accountTransaction->searchAccountTransactionBasedOnUserBranch(
            $request,
            $this->perPage
        );

        return response()->json($accountTransaction);
    }
}
