<?php

namespace App\Http\Controllers\Journals;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Service\Journal\TrialBalanceService;
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
        $totalDebit = $this->trialBalanceService->getTotalDebit($request)->whereYear('date', Carbon::now())
            ->sum('amount');
        $totalCredit = $this->trialBalanceService->getTotalCredit($request)->whereYear('date', Carbon::now())
            ->sum('amount');

        return response()->json([
            'trial_balances' => $this->trialBalanceService->data(),
            'total_debit' => 'Rp.'.number_format($totalDebit, 2),
            'total_credit' => 'Rp.'.number_format($totalCredit, 2),
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
