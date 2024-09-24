<?php

namespace App\Http\Controllers\Journals;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\AccountTransaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
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

    public function detailAccountTransaction(Account $account): JsonResponse
    {
        $accountTransaction = AccountTransaction::with('account', 'account.subAccount')->whereHas(
            'account',
            function ($query) use ($account) {
                $query->where('id', $account->id);
            }
        )->get()->groupBy('description')->map(function (Collection $item) {
            return [
                'date' => $item->first()->created_at->format('d/m/Y'),
                'description' => $item->first()->description,
                'debit' => $item->where('debit', '>', 0)->map(function ($transaction) {
                    return [
                        'amount' => 'Rp.'.number_format($transaction->debit) ?? '-',
                    ];
                })->values(),
                'credit' => $item->where('credit', '>', 0)->map(function ($transaction) {
                    return [
                        'amount' => 'Rp.'.number_format($transaction->credit) ?? '-',
                    ];
                })->values(),
            ];
        })->filter()->values();


        $totalCredit = AccountTransaction::with('account', 'account.subAccount')->whereHas(
            'account',
            function ($query) use ($account) {
                $query->where('id', $account->id);
            }
        )->sum('credit');


        $totalDebit = AccountTransaction::with('account', 'account.subAccount')->whereHas(
            'account',
            function ($query) use ($account) {
                $query->where('id', $account->id);
            }
        )->sum('debit');


        return response()->json([
            'account_transaction' => $accountTransaction,
            'total_credit' => 'Rp.'.number_format($totalCredit),
            'total_debit' => 'Rp.'.number_format($totalDebit),
            'total_balance' => 'Rp.'.number_format($totalDebit - $totalCredit),
        ]);
    }


}
