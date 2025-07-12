<?php

namespace App\Support\Journal;

use App\Models\AccountCategory;
use App\Models\AccountingPeriod;
use Carbon\Carbon;
use Illuminate\Http\Request;
use function App\Helper\currencyFormat;

class FinancialReportService
{

    public function data(Request $request)
    {
        $data = AccountCategory::with('children', 'accounts', 'accounts.accountTransaction', 'accounts.children')
            ->whereNull('parent_id')
            ->get();

        return self::formattedData($data, $request);
    }


    public function formattedData($data, $request)
    {
        return $data->map(function ($item) use ($request) {
            $total = 0;
            $totalEachCategories = 0;
            foreach ($item->children as $children) {
                foreach ($children->accounts as $account) {
                    $debit = 0;
                    $childDebit = 0;
                    if ($account->trial_balance_type === 'debit') {
                        $debit = $this->getFilteredTransactionSum($account, 'debit', $request);
                        $childDebit = $account->children->sum(function ($child) use ($request, $account) {
                            return $this->getFilteredTransactionSum($child, 'debit', $request);
                        });
                    }

                    $credit = $this->getFilteredTransactionSum($account, 'credit', $request);
                    $childCredit = $account->children->sum(function ($child) use ($request) {
                        return $this->getFilteredTransactionSum($child, 'credit', $request);
                    });

                    if ($account->trial_balance_type === 'debit') {
                        $balance = ($debit + $childDebit) - $childCredit;
                    } else {
                        $balance = $credit + $childCredit;
                    }

                    $total += $balance;
                }
                $totalEachCategories += $total;
            }

            return [
                'id' => $item->id,
                'name' => $item->name,
                'total_each_categories' => currencyFormat($totalEachCategories),
                'sub_categories' => $item->children->map(function ($child) use ($request) {
                    $total = 0;
                    foreach ($child->accounts as $account) {
                        $debit = 0;
                        $childDebit = 0;
                        if ($account->trial_balance_type === 'debit') {
                            $debit = $this->getFilteredTransactionSum($account, 'debit', $request);
                            $childDebit = $account->children->sum(function ($child) use ($request, $account) {
                                return $this->getFilteredTransactionSum($child, 'debit', $request);
                            });
                        }

                        $credit = $this->getFilteredTransactionSum($account, 'credit', $request);
                        $childCredit = $account->children->sum(function ($child) use ($request) {
                            return $this->getFilteredTransactionSum($child, 'credit', $request);
                        });

                        if ($account->trial_balance_type === 'debit') {
                            $balance = ($debit + $childDebit) - $childCredit;
                        } else {
                            $balance = $credit + $childCredit;
                        }

                        $total += $balance;
                    }
                    return [
                        'id' => $child->id,
                        'name' => $child->name,
                        'total' => currencyFormat($total),
                        'accounts' => $child->accounts->map(function ($account) use ($request, $total) {
                            $debit = 0;
                            $childDebit = 0;
                            if ($account->trial_balance_type === 'debit') {
                                $debit = $this->getFilteredTransactionSum($account, 'debit', $request);
                                $childDebit = $account->children->sum(function ($child) use ($request, $account) {
                                    return $this->getFilteredTransactionSum($child, 'debit', $request);
                                });
                            }

                            $credit = $this->getFilteredTransactionSum($account, 'credit', $request);
                            $childCredit = $account->children->sum(function ($child) use ($request) {
                                return $this->getFilteredTransactionSum($child, 'credit', $request);
                            });

                            if ($account->trial_balance_type === 'debit') {
                                $balance = ($debit + $childDebit) - $childCredit;
                            } else {
                                $balance = $credit + $childCredit;
                            }
                            return [
                                'id' => $account->id,
                                'name' => $account->name,
                                'trial_balance_type' => $account->trial_balance_type,
                                'balance' => currencyFormat($balance),
                            ];
                        })
                    ];
                })
            ];
        });
    }


    public function getFilteredTransactionSum($account, $type, Request $request): float
    {
        $transactions = $account->accountTransaction()->where('entries_type', $type)
            ->whereYear('date', AccountingPeriod::first()->year);

        if ($request->branch_id) {
            $transactions->where('branch_id', $request->branch_id);
        }


        if ($request->month) {
            $transactions->whereMonth('date', $request->month);
        }

        return $transactions->sum('amount');
    }


    public function filter()
    {

    }
}
