<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\AccountCategory\SubAccountImportRequest;
use App\Http\Requests\Master\AccountCategory\SubAccountRequest;
use App\Models\Account;
use App\Models\SubAccount;
use App\Service\Accounts\SubAccountService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Throwable;

class SubAccountController extends Controller
{
    private Account $account;
    private SubAccountService $subAccountService;

    public function __construct()
    {
        $this->account = new Account();
        $this->subAccountService = new SubAccountService();
    }

    public function index(): View
    {
        return view('pages.account-master.sub-account.index');
    }

    public function data(): JsonResponse
    {
        return response()->json($this->subAccountService->data());
    }

    public function search(Request $request): JsonResponse
    {
        return response()->json($this->subAccountService->search($request));
    }

    public function accountData(Request $request): JsonResponse
    {
        $accounts = $this->account->getAccount($request);
        return response()->json($accounts);
    }

    public function store(SubAccountRequest $request): JsonResponse
    {
        SubAccount::create($request->validated());
        return response()->json(['message' => 'data berhasil disimpan']);
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
        return response()->json(['message' => 'Data berhasil diupdate']);
    }

    public function destroy(Request $request, SubAccount $subAccount): JsonResponse
    {
        $implodeID = implode(',', $request->get('id'));
        $explodeID = explode(',', $implodeID);
        $subAccount->whereIn('id', $explodeID)->delete();

        return response()->json([
            'message' => 'Data berhasil dihapus',
        ]);
    }

    /**
     * @throws Throwable
     */
    public function import(SubAccountImportRequest $request): JsonResponse
    {
        $this->subAccountService->import($request);
        return response()->json(['message' => 'Data berhasil diimport']);
    }
}
