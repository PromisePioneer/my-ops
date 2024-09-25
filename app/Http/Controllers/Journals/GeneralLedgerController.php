<?php

namespace App\Http\Controllers\Journals;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Service\Journal\GeneralLedgerService;
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

    public function data(): JsonResponse
    {
        return response()->json($this->generalLedgerService->getAccountData());
    }

    public function detail(Account $account): View
    {
        return view('pages.journals.general-ledger.detail', compact('account'));
    }

    public function detailAccountTransaction(Account $account): JsonResponse
    {
        $accountTransaction = $this->generalLedgerService->getDetailGeneralLedger($account);
        $totalCredit = $this->generalLedgerService->getAccountTransaction($account)->sum('credit');
        $totalDebit = $this->generalLedgerService->getAccountTransaction($account)->sum('debit');

        return response()->json([
            'account_transaction' => $accountTransaction,
            'total_credit' => 'Rp.'.number_format($totalCredit),
            'total_debit' => 'Rp.'.number_format($totalDebit),
            'total_balance' => 'Rp.'.number_format($totalDebit - $totalCredit),
        ]);
    }


    public function filter(Account $account, Request $request): JsonResponse
    {
        $accountTransaction = $this->generalLedgerService->filterByPeriod($account, $request->month, $request->year);
        $totalDebit = $this->generalLedgerService->getAccountTransaction($account)
            ->whereMonth('date', $request->month)
            ->whereYear('date', $request->year)
            ->sum('debit');
        $totalCredit = $this->generalLedgerService->getAccountTransaction($account)
            ->whereMonth('date', $request->month)
            ->whereYear('date', $request->year)
            ->sum('credit');

        return response()->json([
            'account_transaction' => $accountTransaction,
            'total_credit' => 'Rp.'.number_format($totalCredit),
            'total_debit' => 'Rp.'.number_format($totalDebit),
            'total_balance' => 'Rp.'.number_format($totalDebit - $totalCredit),
        ]);
    }


}
