<?php

namespace App\Http\Controllers\Master\Accounting;

use AllowDynamicProperties;
use App\Http\Controllers\Controller;
use App\Http\Requests\Master\Accounting\Account\AccountRequest;
use App\Models\Account;
use App\Models\Company;
use App\Models\Master\Common\Branch;
use App\Support\Master\Accounting\Accounts\Service\AccountService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

#[AllowDynamicProperties] class AccountController extends Controller
{
    public function __construct()
    {
        $this->account = new Account();
        $this->branch = new Branch();
        $this->accountService = new AccountService();
    }

    /**
     * @throws AuthorizationException
     */
    public function index(): View
    {
        $this->authorize('view', Account::class);
        return view('pages.master.accounting.accounts.index');
    }


    public function createChildAccount(AccountRequest $request, Account $account): JsonResponse
    {
        $this->authorize('create', $account);
        Account::create([
            'name' => $request->name,
            'code' => $request->code,
            'parent_id' => $account->id,
            'category_id' => $request->category_id,
        ]);
        return response()->json(['message' => 'Data berhasil disimpan']);
    }

    /**
     * @throws AuthorizationException
     */
    public function data(Request $request): JsonResponse
    {
        $this->authorize('view', Account::class);
        $accounts = $this->accountService->data($request);
        return response()->json($accounts);
    }

    /**
     * @throws AuthorizationException
     */
    public function search(Request $request): JsonResponse
    {
        $this->authorize('view', Account::class);
        $accounts = $this->accountService->search($request);
        return response()->json($accounts);
    }

    /**
     * @throws AuthorizationException
     */
    public function store(AccountRequest $request): JsonResponse
    {
        $this->authorize('create', Account::class);

        Account::create([
            'company_id' => $request->company_id ?? $request->user()->company_id,
            'name' => $request->name,
            'code' => $request->code,
            'parent_id' => $request->parent_id,
            'category_id' => $request->category_id,
            'trial_balance_type' => $request->trial_balance_type,
        ]);

        return response()->json([
            'message' => 'Data berhasil disimpan',
        ]);
    }


    /**
     * @throws AuthorizationException
     */
    public function edit(Account $account): JsonResponse
    {
        $this->authorize('update', $account);
        return response()->json($account);
    }

    /**
     * @throws AuthorizationException
     */
    public function update(AccountRequest $request, Account $account): JsonResponse
    {
        $this->authorize('update', $account);
        $account->update($request->validated());

        return response()->json([
            'message' => 'data berhasil disimpan',
        ]);
    }


    /**
     * @throws AuthorizationException
     */
    public function destroy(Request $request, Account $account): JsonResponse
    {
        $this->authorize('delete', $account);
        $implodeID = implode(',', $request->get('id'));
        $explodeID = explode(',', $implodeID);
        $account->whereIn('id', $explodeID)->delete();

        return response()->json([
            'message' => 'data berhasil dihapus',
        ]);
    }


    public function getAccounts(Request $request): JsonResponse
    {
        return response()->json($this->accountService->getAccounts($request));
    }

    public function selectedAccount(Account $account): array
    {
        $account = $account->where('id', $account->id)->first();

        return [
            'id' => $account->id,
            'name' => $account->name,
            'code' => $account->code,
        ];
    }


    public function stockAccounts(Request $request): JsonResponse
    {
        return response()->json($this->accountService->getStockAccounts($request));
    }


    public function assetAccounts(Request $request, ?Company $company): JsonResponse
    {
        return response()->json($this->accountService->getAssetAccounts($request, $company));
    }


    public function kasAndLeverageAccounts(Request $request): JsonResponse
    {
        return response()->json($this->accountService->kasAndLeverageAccounts($request));
    }

    public function kasAccounts(Request $request): JsonResponse
    {
        return response()->json($this->accountService->kasAccounts($request));
    }

    public function parentAccounts(Request $request)
    {
        return response()->json($this->accountService->parentAccount($request));
    }

    public function filter(Request $request)
    {
        $this->authorize('filterByCompany', Account::class);
        return response()->json($this->accountService->filter($request));
    }

}
