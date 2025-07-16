<?php

namespace App\Http\Controllers\Accounting\Journals;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\AccountingPeriod;
use App\Models\AccountTransaction;
use App\Support\Journal\GeneralLedgerService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GeneralLedgerController extends Controller
{
    private GeneralLedgerService $generalLedgerService;

    public function __construct()
    {
        $this->generalLedgerService = new GeneralLedgerService();
    }

    public function index(): View
    {
        return view('pages.journals.general-ledger.index');
    }

    public function data(Request $request): JsonResponse
    {

        $data = $this->generalLedgerService->getAccountData($request)->get();
        return response()->json($data);
    }

    public function detail(Account $account): View
    {
        return view('pages.journals.general-ledger.detail', compact('account'));
    }

    public function detailAccountTransaction(Account $account): JsonResponse
    {
        $accountTransaction = $this->generalLedgerService
            ->getDetailGeneralLedger($account)
            ->get();


        $totalDebit = $this->generalLedgerService->getDetailGeneralLedger($account)
            ->where('entries_type', 'debit')
            ->sum('amount');


        $totalCredit = $this->generalLedgerService->getDetailGeneralLedger($account)
            ->where('entries_type', 'credit')
            ->sum('amount');


        return response()->json([
            'account_transaction' => $accountTransaction,
            'total_credit' => 'Rp.' . number_format(
                    $totalCredit,
                    2,
                    ",",
                    "."
                ),
            'total_debit' => 'Rp.' . number_format(
                    $totalDebit,
                    2,
                    ",",
                    "."
                ),
            'total_balance' => 'Rp.' . number_format(
                    $totalDebit - $totalCredit,
                    2,
                    ",",
                    "."
                ),
        ]);
    }


    public function filter(Account $account, Request $request): JsonResponse
    {
        $accountTransaction = $this->generalLedgerService
            ->getDetailGeneralLedger($account)
            ->whereMonth('date', $request->month)
            ->get();


        $totalDebit = $this->generalLedgerService->getDetailGeneralLedger($account)
            ->whereMonth('date', $request->month)
            ->where('entries_type', 'debit')
            ->sum('amount');


        $totalCredit = $this->generalLedgerService->getDetailGeneralLedger($account)
            ->whereMonth('date', $request->month)
            ->where('entries_type', 'credit')
            ->sum('amount');


        return response()->json([
            'account_transaction' => $accountTransaction,
            'total_credit' => 'Rp.' . number_format($totalCredit),
            'total_debit' => 'Rp.' . number_format($totalDebit),
            'total_balance' => 'Rp.' . number_format($totalDebit - $totalCredit),
        ]);
    }


}
