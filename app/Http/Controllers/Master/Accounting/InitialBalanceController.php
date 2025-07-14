<?php

namespace App\Http\Controllers\Master\Accounting;

use AllowDynamicProperties;
use App\Http\Controllers\Controller;
use App\Http\Requests\InitialBalanceRequest;
use App\Models\Account;
use App\Models\AccountingPeriod;
use App\Models\AccountTransaction;
use App\Models\Master\Common\Branch;
use App\Support\Master\Accounting\InitialBalances\Service\InitialBalanceService;
use Carbon\Carbon;
use DB;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use function App\Helper\currencyFormat;

#[AllowDynamicProperties] class InitialBalanceController extends Controller
{
    public function __construct()
    {
        $this->account = new Account();
        $this->initialBalanceService = new InitialBalanceService();
        $this->branch = new Branch();
        $this->accountingPeriod = new AccountingPeriod();
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
        $query = Account::with(['children', 'accountTransaction'])
            ->where('company_id', $request->company_id ?? $request->user()->company_id)
            ->whereNull('parent_id');
        $initialBalance = $this->initialBalanceService->formattedTotalInitialBalanceData($query, $request);
        $totalDebit = '0';
        $totalCredit = '0';


        foreach ($initialBalance as $item) {
            (float)$totalDebit += $item['initial_balance_debit'];
            (float)$totalCredit += $item['initial_balance_credit'];
        }

        return response()->json([
            'initial_balances' => $this->initialBalanceService->data($request),
            'total_debit' => currencyFormat(bcsub($totalDebit, '0', 2)),
            'total_credit' => currencyFormat(bcsub($totalCredit, '0', 2)),
        ]);
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
            'date' => Carbon::parse(
                $this->accountingPeriod->first()->year . '-' . Carbon::now()->month . '-' . Carbon::now()->day
            )->endOfYear()->subYear(),
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
        $data = AccountTransaction::where('account_id', $account->id)
            ->where('branch_id', $branchId)
            ->where('transaction_type', 'SA')
            ->where('entries_type', $request->input('entries_type'))
            ->whereYear('date', $request->input('year') ?? Carbon::now()->subYear())->first() ?? $account;

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
            ->where('account_transactions.transaction_type', 'TR')
            ->whereIn('accounts.id', $explodeID)
            ->select('account_transactions.id as account_transaction_id')
            ->get();


        if ($transactions->isNotEmpty()) {
            return response()->json([
                'message' => 'Akun ini sudah mempunyai transaksi. ',
            ], 422);
        }

        $transactionIds = $transactions->pluck('account_transaction_id')->toArray();
        DB::table('account_transactions')->whereIn('id', $transactionIds)->delete();


        return response()->json([
            'message' => 'Transactions successfully deleted',
        ], 200);
    }

}
