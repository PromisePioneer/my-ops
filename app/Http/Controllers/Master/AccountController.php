<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\Account\AccountImportRequest;
use App\Http\Requests\Master\Account\AccountRequest;
use App\Imports\AccountImport;
use App\Models\Account;
use App\Models\Branch;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class AccountController extends Controller
{
    public int $perPage = 10;
    private Branch $branch;
    private Account $account;

    public function __construct()
    {
        $this->account = new Account();
        $this->branch = new Branch();
    }

    /**
     * @throws AuthorizationException
     */
    public function index(): View
    {
        $this->authorize('view', Account::class);
        return view('pages.account-master.account.index');
    }

    /**
     * @throws AuthorizationException
     */
    public function data(Request $request): JsonResponse
    {
        $this->authorize('view', Account::class);
        $accounts = $this->account->getAccountsBasedOnUserBranch($request->branch_id, $this->perPage);
        return response()->json($accounts);
    }

    /**
     * @throws AuthorizationException
     */
    public function branchData(Request $request): JsonResponse
    {
        $this->authorize('view', Account::class);
        $response = $this->branch->getData($request);
        return response()->json($response);
    }

    /**
     * @throws AuthorizationException
     */
    public function search(Request $request): JsonResponse
    {
        $this->authorize('view', Account::class);
        $accounts = $this->account->searchAccounts($request, $this->perPage);
        return response()->json($accounts);
    }

    public function filter(Branch $branch): JsonResponse
    {
        $filter = $this->account->filteringAccountBasedOnBranch($branch->id, $this->perPage);
        return response()->json($filter);
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

    public function getSelectedBranch(Account $account): JsonResponse
    {
        $selectedBranch = $this->branch->getSelectedData($account->branch_id);
        return response()->json($selectedBranch);
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
    public function destroy(Account $account): JsonResponse
    {
        $this->authorize('delete', $account);
        return response()->json($account->delete());
    }

    /**
     * @throws AuthorizationException
     */
    public function import(AccountImportRequest $request): JsonResponse
    {
        $this->authorize('import', Account::class);
        $file = $request->file('file_import');
        Excel::import(new AccountImport(), $file);

        return response()->json([
            'message' => 'Data berhasil diimport',
        ]);
    }
}
