<?php

namespace App\Http\Controllers\Accounting\Transaction;

use App\Http\Controllers\Controller;
use App\Http\Requests\InitialBalanceRequest;
use App\Models\Account;
use App\Models\AccountTransaction;
use App\Models\Branch;
use App\Service\InitialBalanceService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InitialBalanceController extends Controller
{


    private Account $account;
    private InitialBalanceService $initialBalanceService;
    private Branch $branch;

    public function __construct()
    {
        $this->account = new Account();
        $this->initialBalanceService = new InitialBalanceService();
        $this->branch = new Branch();
    }

    public function index(): View
    {
        return view('pages.account-master.initial-balances.index');
    }


    public function data(Request $request): JsonResponse
    {
        $data = $this->initialBalanceService->data($request);
        return response()->json($data);
    }


    public function getAccountData(Request $request): JsonResponse
    {
        return response()->json($this->account->getParentAccount($request));
    }

    public function getBranchData(Request $request): JsonResponse
    {
        return response()->json($this->branch->getData($request));
    }


    public function search()
    {
    }


    public function filter(Request $request): JsonResponse
    {
        return response()->json($this->initialBalanceService->filter($request));
    }

    public function store(InitialBalanceRequest $request): JsonResponse
    {
        AccountTransaction::create([
            'branch_id' => $request->branch_id ?? null,
            'date' => $request->date,
            'account_id' => $request->account_id,
            'transaction_type' => 'SA',
            'entries_type' => 'Debit',
            'amount' => $request->amount,
        ]);

        return response()->json(['message' => 'Saldo awal berhasil ditambahkan.']);
    }


    public function edit(AccountTransaction $accountTransaction): JsonResponse
    {
        return response()->json($accountTransaction);
    }

    public function selectedBranch(AccountTransaction $accountTransaction): JsonResponse
    {
        return response()->json($this->branch->getSelectedData($accountTransaction->branch_id));
    }

    public function selectedAccountData(AccountTransaction $accountTransaction): JsonResponse
    {
        return response()->json($this->account->getSelectedAccount($accountTransaction->account_id));
    }


    public function update(InitialBalanceRequest $request, AccountTransaction $accountTransaction): JsonResponse
    {
        $accountTransaction->update([
            'branch_id' => $request?->branch_id ?? null,
            'date' => $request->date,
            'account_id' => $request->account_id,
            'transaction_type' => 'SA',
            'amount' => $request->amount,
        ]);

        return response()->json(['message' => 'Saldo awal berhasil diubah.']);
    }


    public function destroy(AccountTransaction $accountTransaction, Request $request): JsonResponse
    {
        $implodeID = implode(',', $request->get('id'));
        $explodeID = explode(',', $implodeID);
        $accountTransaction->whereIn('id', $explodeID)->delete();

        return response()->json([
            'message' => 'data berhasil dihapus',
        ], 200);
    }

}
