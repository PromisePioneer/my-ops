<?php

namespace App\Http\Controllers\Master\Accounting;

use AllowDynamicProperties;
use App\Http\Controllers\Controller;
use App\Http\Requests\Master\Account\AccountRequest;
use App\Models\Account;
use App\Models\Master\Common\Branch;
use App\Support\Accounts\Service\AccountService;
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
        return view('pages.finance-master-data.account.index');
    }


    public function createChildAccount(AccountRequest $request, Account $account): JsonResponse
    {
        $this->authorize('create', $account);
        Account::create([
            'name' => $request->name,
            'code' => $request->code,
            'parent_id' => $account->id,
        ]);
        return response()->json(['message' => 'Data berhasil disimpan']);
    }

    /**
     * @throws AuthorizationException
     */
    public function data(): JsonResponse
    {
        $this->authorize('view', Account::class);
        $accounts = $this->accountService->data();
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
        Account::create($request->validated());

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
        ], 200);
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


    public function assetAccounts(Request $request): JsonResponse
    {
        return response()->json($this->accountService->getAssetAccounts($request));
    }


    public function kasAccounts(Request $request): JsonResponse
    {
        return response()->json($this->accountService->getKasAccounts($request));
    }

}
