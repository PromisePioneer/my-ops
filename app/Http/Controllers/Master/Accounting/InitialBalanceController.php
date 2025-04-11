<?php

namespace App\Http\Controllers\Master\Accounting;

use AllowDynamicProperties;
use App\Http\Controllers\Controller;
use App\Http\Requests\InitialBalanceRequest;
use App\Models\Account;
use App\Models\AccountTransaction;
use App\Models\Master\Common\Branch;
use App\Support\Master\Accounting\InitialBalances\Service\InitialBalanceService;
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
        return view('pages.master.accounting.initial-balances.index');
    }


    /**
     * @throws AuthorizationException
     */
    public function data(Request $request): JsonResponse
    {
        $this->authorize('view', AccountTransaction::class);
        $data = $this->initialBalanceService->data($request);
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
            $query->where('name', 'like', '%' . $search . '%')
                ->orWhere('code', 'like', '%' . $search . '%')
                ->orWhereHas('children', function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%')
                        ->orWhere('code', 'like', '%' . $search . '%');
                });
        }

        $account = $query->get();

        $data = $account->map(function ($c) {
            $hasChildren = $c->children->isNotEmpty();

            if (!$hasChildren) {
                return [
                    'id' => $c->id,
                    'text' => $c->code . ' ' . $c->name,
                ];
            }


            return [
                'text' => $c->code . ' ' . $c->name,
                'children' => $c->children->filter(function ($child) {
                    return [
                        'id' => $child->id,
                        'text' => $child->code . ' ' . $child->name,
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
        $rawAmount = $request->input('amount');
        $formattedValue = str_replace(',', '.', str_replace('.', '', $rawAmount));
        $amount = number_format((float)$formattedValue, 4, '.', '');


        AccountTransaction::query()->updateOrCreate([
            'branch_id' => $request->branch_id ?? $request->user()->branch_id,
            'account_id' => $request->account_id,
            'entries_type' => $request->entries_type,
        ], [
            'date' => Carbon::now()->subYear()->endOfYear(),
            'transaction_type' => 'SA',
            'amount' => (float)$amount,
        ]);

        return response()->json(['message' => 'Saldo awal berhasil ditambahkan.']);
    }


    /**
     * @throws AuthorizationException
     */
    public function edit(Request $request, Account $account): JsonResponse
    {

        $this->authorize('update', $account);
        $branchId = $request->branch_id ?? $request->user()->branch_id;
        $data = AccountTransaction::where('account_id', $account->id)->where('branch_id', $branchId)->where('transaction_type', 'SA')->where('entries_type', $request->input('entries_type'))->whereYear('date', Carbon::now()->subYear())->first() ?? $account;

        if ($data) {
            $data->amount = (float)$data?->amount;
        }
        return response()->json($data);
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
