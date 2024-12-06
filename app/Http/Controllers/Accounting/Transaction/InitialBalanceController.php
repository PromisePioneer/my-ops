<?php

namespace App\Http\Controllers\Accounting\Transaction;

use AllowDynamicProperties;
use App\Http\Controllers\Controller;
use App\Http\Requests\InitialBalanceRequest;
use App\Models\Account;
use App\Models\AccountTransaction;
use App\Models\Branch;
use App\Service\InitialBalanceService;
use Carbon\Carbon;
use DB;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

#[AllowDynamicProperties] class InitialBalanceController extends Controller
{

    public function __construct()
    {
        $this->account = new Account();
        $this->initialBalanceService = new InitialBalanceService();
        $this->branch = new Branch();
    }

    /**
     * @throws AuthorizationException
     */
    public function index(): View
    {
        $this->authorize('view', AccountTransaction::class);
        return view('pages.finance-master-data.initial-balances.index');
    }


    /**
     * @throws AuthorizationException
     */
    public function data(): JsonResponse
    {
        $this->authorize('view', AccountTransaction::class);
        $data = $this->initialBalanceService->data();
        return response()->json($data);
    }


    /**
     * @throws AuthorizationException
     */
    public function getAccountData(Request $request): JsonResponse
    {
        $this->authorize('view', AccountTransaction::class);
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


    /**
     * @throws AuthorizationException
     */
    public function getBranchData(Request $request): JsonResponse
    {
        $this->authorize('view', AccountTransaction::class);
        return response()->json($this->branch->getData($request));
    }


    /**
     * @throws AuthorizationException
     */
    public function search(Request $request): JsonResponse
    {
        $this->authorize('view', AccountTransaction::class);
        return response()->json($this->initialBalanceService->search($request));
    }


    /**
     * @throws AuthorizationException
     */
    public function filter(Request $request): JsonResponse
    {
        $this->authorize('view', AccountTransaction::class);
        $this->authorize('filterBranch', AccountTransaction::class);
        return response()->json($this->initialBalanceService->filter($request));
    }

    /**
     * @throws AuthorizationException
     */
    public function store(InitialBalanceRequest $request): JsonResponse
    {
        $this->authorize('create', AccountTransaction::class);
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


    /**
     * @throws AuthorizationException
     */
    public function edit(Request $request, AccountTransaction $account): JsonResponse
    {
        $this->authorize('update', $account);
        $branchId = $request->branch_id;
        $data = $account->join(
            'account_transactions',
            'account_transactions.account_id',
            '=',
            'accounts.id'
        )->where('account_transactions.branch_id', $branchId)
            ->whereYear('date', Carbon::now()->subYear())
            ->first();


        return response()->json($data);
    }

    /**
     * @throws AuthorizationException
     */
    public function selectedBranch(Branch $branch): JsonResponse
    {
        $this->authorize('update', AccountTransaction::class);
        return response()->json($this->branch->getSelectedData($branch->id));
    }

    /**
     * @throws AuthorizationException
     */
    public function selectedAccountData(Account $account): JsonResponse
    {
        $this->authorize('update', $account);
        return response()->json($this->account->getSelectedAccount($account->id));
    }


    /**
     * @throws AuthorizationException
     */
    public function update(InitialBalanceRequest $request, AccountTransaction $accountTransaction): JsonResponse
    {
        $this->authorize('update', $accountTransaction);
        $accountTransaction->update([
            'branch_id' => $request?->branch_id ?? null,
            'date' => $request->date,
            'account_id' => $request->account_id,
            'transaction_type' => 'SA',
            'amount' => $request->amount,
        ]);

        return response()->json(['message' => 'Saldo awal berhasil diubah.']);
    }


    /**
     * @throws AuthorizationException
     */
    public function destroy(Account $account, Request $request): JsonResponse
    {
        $this->authorize('delete', $account);
        $implodeID = implode(',', $request->get('id'));
        $explodeID = explode(',', $implodeID);

        $branchId = $request->branch_id;

        $transactions = $account->join(
            'account_transactions',
            'account_transactions.account_id',
            '=',
            'accounts.id'
        )->where('account_transactions.branch_id', $branchId)
            ->whereIn('accounts.id', $explodeID)
            ->whereYear('date', Carbon::now()->subYear())
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
