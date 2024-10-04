<?php

namespace App\Http\Controllers\Accounting\Transaction;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Transaction\SubAccount;
use App\Http\Requests\Transaction\Expenditure\ExpenditureRequest;
use App\Models\Branch;
use App\Models\Expenditure;
use App\Service\ExpenditureServices;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ExpenditureController extends Controller
{
    public int $perPage = 10;

    private ExpenditureServices $expenditureService;

    private Expenditure $expenditure;


    private Branch $branch;

    public function __construct()
    {
        $this->expenditureService = new ExpenditureServices();
        $this->expenditure = new Expenditure();
        $this->branch = new Branch();
    }

    public function index()
    {
        return view('pages.transaction.expenditure.index');
    }

    public function data(Request $request): JsonResponse
    {
        return response()->json(
            $this->expenditure->getDataWithPaginationBasedOnUserBranch($request->user()->branch_id, $this->perPage)
        );
    }

    public function search(Request $request): JsonResponse
    {
        return response()->json($this->expenditure->searchDataWithPagination($request, $this->perPage));
    }

    public function branchData(Request $request): JsonResponse
    {
        return response()->json($this->branch->getData($request));
    }

    public function filterByBranch(Branch $branch): JsonResponse
    {
        return response()->json($this->expenditure->filterDataBasedOnBranch($branch->id, $this->perPage));
    }

    public function debitAccount(Request $request): JsonResponse
    {
        $debitAccount = $this->expenditure->getDebitAccountForExpenditure($request);

        return response()->json($debitAccount);
    }

    public function creditAccount(Request $request): JsonResponse
    {
        $creditAccount = $this->expenditure->getCreditAccountForExpenditure($request);

        return response()->json($creditAccount);
    }

    public function store(ExpenditureRequest $request): JsonResponse
    {
        $this->expenditureService->store($request);

        return response()->json([
            'message' => 'data berhasil disimpan',
        ]);
    }

    public function edit(Expenditure $expenditure): JsonResponse
    {
        return response()->json($expenditure);
    }

    public function selectedDebitAccount(Request $request, Expenditure $expenditure): array
    {
        $subAccount = $this->subAccount->getSelectedSubAccount($request, $expenditure->debit_account_id);

        return [
            'id' => $subAccount->id,
            'name' => $subAccount->name,
        ];
    }

    public function selectedCreditAccount(Expenditure $expenditure): array
    {
        $account = SubAccount::where('id', $expenditure->credit_account_id)->first();

        return [
            'id' => $account->id,
            'name' => $account->name,
        ];
    }

    public function update(ExpenditureRequest $request, Expenditure $expenditure): JsonResponse
    {
        $this->expenditureService->update($request, $expenditure);

        return response()->json([
            'message' => 'data berhasil diubah',
        ]);
    }

    public function confirm(Expenditure $expenditure): JsonResponse
    {
        $this->expenditureService->confirm($expenditure);

        return response()->json([
            'message' => 'data berhasil dikonfirmasi',
        ]);
    }

    public function destroy(Expenditure $expenditure): JsonResponse
    {
        return response()->json($expenditure->delete());
    }
}
