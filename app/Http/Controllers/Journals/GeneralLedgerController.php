<?php

namespace App\Http\Controllers\Journals;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\AccountTransaction;
use App\Models\SubAccount;
use Carbon\CarbonPeriod;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class GeneralLedgerController extends Controller
{
    public function index(): View
    {
        return view('pages.journals.general-ledger.index');
    }

    public function data(): JsonResponse
    {
        $account = Account::all();

        return response()->json($account);
    }

    public function detail(Account $account): View
    {
        $accountTransaction = AccountTransaction::with('account')->get();

        return view('pages.journals.general-ledger.detail', compact('accountTransaction', 'account'));
    }

    public function detailAkunData(Account $account): JsonResponse
    {
        $currentYear = date('Y');

        $subAccounts = SubAccount::where('account_id', $account->id)->pluck('id')->toArray();

        $period = CarbonPeriod::create("$currentYear-01-01", '1 month', "$currentYear-12-31");
        $months = collect($period)->map(function ($date) {
            return $date->format('Y-m');
        });

        $transactions = DB::table('account_transactions')
            ->whereIn('sub_account_id', $subAccounts)
            ->orWhere('account_id', $account->id)
            ->select(
                DB::raw('DATE_FORMAT(date, "%Y-%m") as month'),
                DB::raw('SUM(debit) as total_debit'),
                DB::raw('SUM(credit) as total_credit')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->keyBy('month');

        $monthlyData = $months->map(function ($month) use ($transactions) {
            return [
                'month' => $month,
                'total_debit' => $transactions->has($month) ? $transactions->get($month)->total_debit : 0,
                'total_credit' => $transactions->has($month) ? $transactions->get($month)->total_credit : 0,
            ];
        });

        return response()->json([
            'transactions' => $monthlyData,
            'account' => $account->name,
            'year' => $currentYear,
        ]);
    }
}
