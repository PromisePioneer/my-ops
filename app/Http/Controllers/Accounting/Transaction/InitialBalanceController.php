<?php

namespace App\Http\Controllers\Accounting\Transaction;

use App\Http\Controllers\Controller;
use App\Http\Requests\InitialBalanceRequest;
use App\Models\Account;
use App\Models\AccountTransaction;
use App\Models\Branch;
use App\Service\InitialBalanceService;
use DB;
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
        return view('pages.finance-master-data.initial-balances.index');
    }


    public function data(): JsonResponse
    {
        $data = $this->initialBalanceService->data();
        return response()->json($data);
    }


    public function getAccountData(Request $request): JsonResponse
    {
        $search = $request->input('search');
        $query = Account::with('children')->whereNull('parent_id')
            ->orderby('code')
            ->select('id', 'name', 'code');

        if ($search !== '') {
            $query->where('name', 'like', '%'.$search.'%')
                ->orWhere('code', 'like', '%'.$search.'%')
                ->orWhereHas('children', function ($query) use ($search) {
                    $query->where('name', 'like', '%'.$search.'%')
                        ->orWhere('code', 'like', '%'.$search.'%');
                });
        }

        $account = $query->get();

        $data = $account->map(function ($c) {
            $hasChildren = $c->children->isNotEmpty();

            if (!$hasChildren) {
                return [
                    'id' => $c->id,
                    'text' => $c->code.' '.$c->name,
                ];
            }


            return [
                'text' => $c->code.' '.$c->name,
                'children' => $c->children->filter(function ($child) {
                    return [
                        'id' => $child->id,
                        'text' => $child->code.' '.$child->name,
                    ];
                })->toArray(),
                'disabled' => true,
            ];
        })->toArray();

        return response()->json($data);
    }


    public function getBranchData(Request $request): JsonResponse
    {
        return response()->json($this->branch->getData($request));
    }


    public function search(Request $request)
    {
        return response()->json($this->initialBalanceService->search($request));
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


    public function edit(Request $request, Account $account): JsonResponse
    {
        $year = $request->year;
        $branchId = $request->branch_id;

        $data = $account->join(
            'account_transactions',
            'account_transactions.account_id',
            '=',
            'accounts.id'
        )->where('account_transactions.branch_id', $branchId)
            ->whereYear('account_transactions.date', $year)->first();


        return response()->json($data);
    }

    public function selectedBranch(Branch $branch): JsonResponse
    {
        return response()->json($this->branch->getSelectedData($branch->id));
    }

    public function selectedAccountData(Account $account): JsonResponse
    {
        return response()->json($this->account->getSelectedAccount($account->id));
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


    public function destroy(Account $account, Request $request): JsonResponse
    {
        $implodeID = implode(',', $request->get('id'));
        $explodeID = explode(',', $implodeID);

        $year = $request->year;
        $branchId = $request->branch_id;

        $transactions = $account->join(
            'account_transactions',
            'account_transactions.account_id',
            '=',
            'accounts.id'
        )->where('account_transactions.branch_id', $branchId)
            ->whereYear('account_transactions.date', $year)
            ->whereIn('accounts.id', $explodeID)
            ->select('account_transactions.id as account_transaction_id')
            ->get();


        if ($transactions->isEmpty()) {
            return response()->json([
                'message' => 'No transactions found for the specified accounts',
            ], 404);
        }

        $transactionIds = $transactions->pluck('account_transaction_id')->toArray();
        DB::table('account_transactions')->whereIn('id', $transactionIds)->delete();


        return response()->json([
            'message' => 'Transactions successfully deleted',
        ], 200);
    }

}
