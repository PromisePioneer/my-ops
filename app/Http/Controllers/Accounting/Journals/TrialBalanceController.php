<?php

namespace App\Http\Controllers\Accounting\Journals;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Master\Common\Branch;
use App\Support\Journal\TrialBalanceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TrialBalanceController extends Controller
{

    private Branch $branch;
    private TrialBalanceService $trialBalanceService;

    public function __construct()
    {
        $this->branch = new Branch();
        $this->trialBalanceService = new TrialBalanceService();
    }

    public function index(): View
    {
        return view('pages.journals.trial-balance.index');
    }

    public function data(Request $request): JsonResponse
    {
        $query = Account::with('children', 'accountTransaction')->whereNull('parent_id');
        $trialBalance = $this->trialBalanceService->formattedData($query, $request);
        $totalDebit = '0';
        $totalCredit = '0';

        foreach ($trialBalance as $item) {
            $totalDebit = bcadd($totalDebit, $item['balance_debit'], 2);
            $totalCredit = bcadd($totalCredit, $item['balance_credit'], 2);
        }

        return response()->json([
            'trial_balances' => $this->trialBalanceService->data(),
            'total_debit' => 'Rp.' . number_format(bcsub($totalDebit, '0', 2), 2, '.', '.'),
            'total_credit' => 'Rp.' . number_format(bcsub($totalCredit, '0', 2), 2, '.', '.'),
        ]);
    }


    public function getBranchData(Request $request): JsonResponse
    {
        return response()->json($this->branch->getData($request));
    }


    public function filter(Request $request): JsonResponse
    {
        $trialBalance = $this->trialBalanceService->filter($request)['trial_balance'];
        $totalDebit = '0';
        $totalCredit = '0';

        foreach ($trialBalance as $item) {
            $totalDebit = bcadd($totalDebit, $item['balance_debit'], 2);
            $totalCredit = bcadd($totalCredit, $item['balance_credit'], 2);
        }

        return response()->json([
            'trial_balances' => $this->trialBalanceService->filter($request)['trial_balance'],
            'total_debit' => 'Rp.' . number_format(bcsub($totalDebit, '0', 2), 2, '.', '.'),
            'total_credit' => 'Rp.' . number_format(bcsub($totalCredit, '0', 2), 2, '.', '.'),
        ]);
    }


}
