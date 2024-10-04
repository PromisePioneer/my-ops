<?php

namespace App\Http\Controllers\Accounting\Journals;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Service\Journal\GeneralLedgerService;
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
        $accountTransaction = $this->generalLedgerService
            ->getDetailGeneralLedger($account)
            ->get()
            ->map(function ($query) {
                return [
                    'id' => $query->id,
                    'date' => Carbon::parse($query->date)->format('d/m/Y'),
                    'description' => $query->description,
                    'type' => $query->type,
                    'amount' => number_format($query->amount, 2),
                ];
            });

        $totalDebit = $this->generalLedgerService->getDetailGeneralLedger($account)
            ->where('type', 'debit')
            ->sum('amount');

        $totalCredit = $this->generalLedgerService->getDetailGeneralLedger($account)
            ->where('type', 'credit')
            ->sum('amount');


        return response()->json([
            'account_transaction' => $accountTransaction,
            'total_credit' => 'Rp.'.number_format(
                    $totalCredit,
                    2,
                    ",",
                    "."
                ),
            'total_debit' => 'Rp.'.number_format(
                    $totalDebit,
                    2,
                    ",",
                    "."
                ),
            'total_balance' => 'Rp.'.number_format(
                    $totalDebit - $totalCredit,
                    2,
                    ",",
                    "."
                ),
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
