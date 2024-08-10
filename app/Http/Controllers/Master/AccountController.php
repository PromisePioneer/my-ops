<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\Account\AccountImportRequest;
use App\Http\Requests\Master\Account\AccountRequest;
use App\Imports\AccountImport;
use App\Models\Account;
use App\Models\Branch;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class AccountController extends Controller
{
    private Branch $branch;

    private Account $account;

    public int $perPage = 10;

    public function __construct()
    {
        $this->middleware('permission:lihat akun', ['only' => ['index']]);
        $this->middleware('permission:tambah akun', ['only' => ['create', 'store']]);
        $this->middleware('permission:update akun', ['only' => ['edit', 'update']]);
        $this->middleware('permission:hapus akun', ['only' => ['destroy']]);

        $this->account = new Account();
        $this->branch = new Branch();
    }

    public function index(): View
    {
        return view('pages.account-master.account.index');
    }

    public function data(Request $request): JsonResponse
    {
        $accounts = $this->account->getAccountsBasedOnUserBranch($request->branch_id, $this->perPage);

        return response()->json($accounts);
    }

    public function branchData(Request $request): JsonResponse
    {
        $response = $this->branch->getData($request);

        return response()->json($response);
    }

    public function search(Request $request): JsonResponse
    {
        $accounts = $this->account->searchAccounts($request, $this->perPage);

        return response()->json($accounts);
    }

    public function filter(Branch $branch): JsonResponse
    {
        $filter = $this->account->filteringAccountBasedOnBranch($branch->id, $this->perPage);

        return response()->json($filter);
    }

    public function store(AccountRequest $request): JsonResponse
    {
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

    public function edit(Account $account): JsonResponse
    {
        return response()->json($account);
    }

    public function update(AccountRequest $request, Account $account): JsonResponse
    {
        $account->update($request->validated());

        return response()->json([
            'message' => 'data berhasil disimpan',
        ], 200);
    }

    public function destroy(Account $account): JsonResponse
    {
        return response()->json($account->delete());
    }

    public function import(AccountImportRequest $request): JsonResponse
    {
        $file = $request->file('file_import');
        Excel::import(new AccountImport(), $file);

        return response()->json([
            'message' => 'Data berhasil diimport',
        ]);
    }
}
