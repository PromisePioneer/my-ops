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
        $data = AccountCategory::with(['children', 'accounts', 'accounts.accountTransaction', 'accounts.children'])
            ->whereNull('parent_id')
            ->get();

        return self::formattedData($data, $request);
    }

    public function formattedData($data, $request)
    {
        return $data->map(function ($item) use ($request) {
            $totalEachCategories = 0;

            $subCategories = $item->children->map(function ($child) use ($request) {
                $subCategoryTotal = 0;

                $accounts = $child->accounts->map(function ($account) use ($request) {
                    $balance = $this->calculateAccountBalance($account, $request);
                    return [
                        'id' => $account->id,
                        'name' => $account->name,
                        'trial_balance_type' => $account->trial_balance_type,
                        'balance' => currencyFormat($balance),
                        'raw_balance' => $balance, // Keep raw for calculation
                    ];
                });

                // Sum all account balances for this sub-category
                $subCategoryTotal = $accounts->sum('raw_balance');

                return [
                    'id' => $child->id,
                    'name' => $child->name,
                    'total' => currencyFormat($subCategoryTotal),
                    'raw_total' => $subCategoryTotal, // Keep raw for parent calculation
                    'accounts' => $accounts->map(function ($account) {
                        // Remove raw_balance from final output
                        unset($account['raw_balance']);
                        return $account;
                    })
                ];
            });

            // Sum all sub-category totals
            $totalEachCategories = $subCategories->sum('raw_total');

            return [
                'id' => $item->id,
                'name' => $item->name,
                'total_each_categories' => currencyFormat($totalEachCategories),
                'sub_categories' => $subCategories->map(function ($subCategory) {
                    // Remove raw_total from final output
                    unset($subCategory['raw_total']);
                    return $subCategory;
                })
            ];
        });
    }

    /**
     * Calculate balance for an account including its children
     */
    private function calculateAccountBalance($account, Request $request): float
    {
        // Get parent account transactions
        $parentDebit = 0;
        $parentCredit = 0;

        if ($account->trial_balance_type === 'debit') {
            $parentDebit = $this->getFilteredTransactionSum($account, 'debit', $request);
        }
        $parentCredit = $this->getFilteredTransactionSum($account, 'credit', $request);

        // Get children transactions
        $childDebit = $account->children->sum(function ($child) use ($request) {
            return $this->getFilteredTransactionSum($child, 'debit', $request);
        });

        $childCredit = $account->children->sum(function ($child) use ($request) {
            return $this->getFilteredTransactionSum($child, 'credit', $request);
        });

        // Calculate balance based on trial balance type
        if ($account->trial_balance_type === 'debit') {
            // For debit accounts: (Parent Debit + Child Debit) - (Parent Credit + Child Credit)
            return ($parentDebit + $childDebit) - ($parentCredit + $childCredit);
        } else {
            // For credit accounts: (Parent Credit + Child Credit) - (Parent Debit + Child Debit)
            return ($parentCredit + $childCredit) - ($parentDebit + $childDebit);
        }
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
        // Implementation needed
    }
}
