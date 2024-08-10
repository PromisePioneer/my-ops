<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\AccountCategory\SubAccountImportRequest;
use App\Http\Requests\Master\AccountCategory\SubAccountRequest;
use App\Imports\SubAccountImport;
use App\Models\Account;
use App\Models\SubAccount;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class SubAccountController extends Controller
{
    private SubAccount $subAccount;

    private Account $account;

    public function __construct()
    {
        $this->middleware('permission:lihat kategori akun', ['only' => ['index']]);
        $this->middleware('permission:tambah kategori akun', ['only' => ['create', 'store']]);
        $this->middleware('permission:update kategori akun', ['only' => ['edit', 'update']]);
        $this->middleware('permission:hapus kategori akun', ['only' => ['destroy']]);

        $this->subAccount = new SubAccount();
        $this->account = new Account();
    }

    public function index(): View
    {
        return view('pages.account-master.sub-account.index');
    }

    public function data(): JsonResponse
    {
        $subAccounts = $this->subAccount->getSubAccountBasedOnUserBranch();

        return response()->json($subAccounts);
    }

    public function search(Request $request): JsonResponse
    {
        $search = $this->subAccount->searchSubAccountBasedOnUserBranch($request);

        return response()->json($search);
    }

    public function accountData(Request $request): JsonResponse
    {
        $accounts = $this->account->getAccountDataAndSpecificBranchWithoutPagination($request);

        return response()->json($accounts);
    }

    public function store(SubAccountRequest $request): JsonResponse
    {
        SubAccount::create($request->validated());

        return response()->json([
            'message' => 'data berhasil disimpan',
        ]);
    }

    public function edit(SubAccount $subAccount): JsonResponse
    {
        return response()->json($subAccount);
    }

    public function selectedAccount(SubAccount $subAccount): JsonResponse
    {
        return response()->json($this->account->getSelectedAccount($subAccount->account_id));
    }

    public function update(SubAccountRequest $request, SubAccount $subAccount): JsonResponse
    {
        $subAccount->update($request->validated());

        return response()->json([
            'message' => 'Data berhasil diupdate',
        ]);
    }

    public function destroy(SubAccount $subAccount): JsonResponse
    {
        $subAccount->delete();

        return response()->json([
            'message' => 'Data berhasil dihapus',
        ]);
    }

    public function import(SubAccountImportRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            SubAccount::join('accounts', 'accounts.id', 'sub_accounts.account_id')
                ->whereIn('accounts.branch_id', [Auth::user()->branch_id])
                ->delete();

            $file = $request->file('file_import');
            Excel::import(new SubAccountImport(), $file);

            DB::commit();

            return response()->json([
                'message' => 'Data berhasil diimport',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
