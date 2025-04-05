<?php

namespace App\Http\Controllers\Accounting\Journals;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Master\Common\Branch;
use App\Support\Journal\TrialBalanceService;
use Carbon\Carbon;
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

        $startDate = Carbon::now()->subYear()->endOfYear()->format('Y-m-d');
        $endDate = Carbon::now()->format('Y-m-d');

        $query = Account::with('children', 'accountTransaction')->whereNull('parent_id');

        $test = $this->trialBalanceService->formattedData($query, $request);
        $totalDebit = '0';
        $totalCredit = '0';
        foreach ($test as $item) {
            $totalDebit = bcadd($totalDebit, $item['balance_debit'], 2);
            $totalCredit = bcadd($totalCredit, $item['balance_credit'], 2);
        }
        return response()->json([
            'trial_balances' => $this->trialBalanceService->data(),
            'total_debit' => $totalDebit,
            'total_credit' => bcsub($totalCredit, '0', 2),
        ]);
    }


    public function getBranchData(Request $request): JsonResponse
    {
        return response()->json($this->branch->getData($request));
    }


    public function filter(Request $request): JsonResponse
    {
        return response()->json([
            'trial_balances' => $this->trialBalanceService->filter($request)['trial_balance'],
            'total_debit' => $this->trialBalanceService->filter($request)['total_debit'],
            'total_credit' => $this->trialBalanceService->filter($request)['total_credit'],
        ]);
    }


}
