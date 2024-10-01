<?php

namespace App\Service\Journal;

use App\Models\Account;
use App\Models\AccountTransaction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class FinancialReportService
{
    public function getFixedAssets()
    {
        $data = Account::with('accountTransaction')
            ->whereBetween('code', ['121', '126']);

        return self::formattedFixedData($data);
    }


    private static function formattedFixedData($fixedAssets)
    {
        return $fixedAssets->get()->map(function ($query) {
            return [
                'name' => $query->name,
                'amount' => 'Rp.'.number_format(
                        $query->accountTransaction->where('type', 'debit')->sum('amount'),
                        2
                    ),
            ];
        });
    }

    public function getTotalFixedAssets()
    {
        $totalFixedAssets = AccountTransaction::with('account')->whereHas('account', function ($query) {
            $query->whereBetween('code', ['121', '126']);
        })->where('type', 'debit')->sum('amount');

        return $this->getDepreciationAssetAccount() + $totalFixedAssets;
    }

    public function getDepreciationAssetAccount()
    {
        return AccountTransaction::with('account')->whereHas('account', function ($query) {
            $query->where('code', '130');
        })->where('type', 'credit')->sum('amount');
    }

    public function getCurrentAssetAccount()
    {
        $data = Account::with('children')
            ->whereBetween('code', ['111', '115'])
            ->whereNull('parent_id');

        return self::currentAssetFormattedData($data);
    }

    public function currentAssetFormattedData(Builder $asset, ?Request $request = null)
    {
        return $asset->get()->map(function ($account) use ($request) {
            $debit = $this->getFilteredTransactionSum($account, 'debit', $request);
            $childDebit = $account->children->sum(function ($child) use ($request) {
                return $this->getFilteredTransactionSum($child, 'debit', $request);
            });

            return [
                'account_name' => $account->name,
                'amount' => 'Rp.'.number_format($debit + $childDebit, 2),
            ];
        });
    }

    public function getFilteredTransactionSum($account, $type, ?Request $request): float
    {
        $transactions = $account->accountTransaction()->where('type', $type);

        if ($request?->branch_id) {
            $transactions->where('branch_id', $request->branch_id);
        }

        if ($request?->year) {
            $transactions->whereYear('date', $request->year);
        }

        if ($request?->month) {
            $transactions->whereMonth('date', $request->month);
        }

        return $transactions->sum('amount');
    }


    public function getCurrentDebtAccount()
    {
        $data = Account::with('children', 'accountTransaction')
            ->whereBetween('code', ['211', '216'])
            ->whereNull('parent_id');

        return self::formattedDebtAccount($data);
    }


    public function formattedDebtAccount(Builder $debt, ?Request $request = null)
    {
        return $debt->get()->map(function ($account) use ($request) {
            $credit = $this->getFilteredTransactionSum($account, 'credit', $request);
            $childCredit = $account->children->sum(function ($child) use ($request) {
                return $this->getFilteredTransactionSum($child, 'credit', $request);
            });

            return [
                'account_name' => $account->name,
                'amount' => 'Rp.'.number_format($credit + $childCredit, 2),
            ];
        });
    }


    public function getTotalCurrentAssetAmount()
    {
        return AccountTransaction::with('account')->whereHas('account', function (Builder $query) {
            $query->whereBetween('code', ['111', '115']);
        })->sum('amount');
    }


    public function getTotalCurrentDebt()
    {
        return AccountTransaction::with('account')->whereHas('account', function (Builder $query) {
            $query->whereBetween('code', ['211', '216']);
        })->where('type', 'credit')
            ->sum('amount');
    }

    public function filter(Request $request): array
    {
        $year = $request->input('year');
        $month = $request->input('month');
        $branchId = $request->input('branch_id');

        $getFixedAsset = Account::with('children', 'accountTransaction')
            ->whereBetween('code', ['121', '126']);


        $getTotalFixedAsset = AccountTransaction::with('account')->whereHas('account', function ($query) {
            $query->whereBetween('code', ['121', '126']);
        })->where('type', 'debit');

        $getDepreciationAsset = AccountTransaction::with('account')->whereHas('account', function ($query) {
            $query->where('code', '130');
        });


        if ($branchId) {
            $getFixedAsset->whereHas('accountTransaction', function ($query) use ($branchId) {
                $query->where('branch_id', $branchId);
            });

            $getDepreciationAsset->sum('amount');
            $getTotalFixedAsset->where('branch_id', $branchId);
        }


        return [
            'fixed_asset' => $this->formattedFixedData($getFixedAsset),
            'total_depreciation_asset' => number_format($getDepreciationAsset->sum('amount'), 2),
            'total_fixed_asset' => number_format($getTotalFixedAsset->sum('amount'), 2),
        ];
    }


}