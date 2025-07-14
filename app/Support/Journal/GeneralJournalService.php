<?php

namespace App\Support\Journal;

use AllowDynamicProperties;
use App\Models\AccountingPeriod;
use App\Models\AccountTransaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use function App\Helper\currencyFormat;
use function App\Helper\formatDate;

#[AllowDynamicProperties] class GeneralJournalService
{
    public function __construct()
    {
        $this->accountingPeriod = new AccountingPeriod();
    }


    public function data(Request $request)
    {
        $generalJournal = AccountTransaction::with('account')
            ->whereHas('account.parent', function ($query) use ($request) {
                $query->where('company_id', $request->session()->get('company_session'));
            })
            ->where('transaction_type', 'TR')
            ->whereYear('date', $this->accountingPeriod->query()->first()->year)
            ->orderBy('date')
            ->get();

        return self::formattedData($generalJournal);
    }


    private static function formattedData($generalJournal)
    {
        return $generalJournal->map(function ($item) {
            return [
                'id' => $item->id,
                'branch_name' => $item->branch->name,
                'date' => formatDate($item->date),
                'amount' => currencyFormat($item->amount),
                'type' => $item->entries_type,
                'account' => $item->account?->code . ' ' . $item->account?->name,
                'description' => $item->description,
            ];
        });
    }


    public function filter(Request $request)
    {

        $branch = $request->input('branch_id');
        $year = $request->input('year');
        $month = $request->input('month');

        $query = AccountTransaction::with('account')->orderBy('date');

        if ($branch) {
            $query->where('branch_id', $branch);
        }

        if ($year) {
            $query->whereYear('date', $year);
        }

        if ($month) {
            $query->whereMonth('date', $month);
        }

        if ($year && $month) {
            $query->orWhereYear('date', $year)
                ->whereMonth('date', $month);
        }

        $data = $query->get();
        return self::formattedData($data);
    }

}
