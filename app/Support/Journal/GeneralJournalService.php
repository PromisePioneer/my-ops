<?php

namespace App\Support\Journal;

use App\Models\AccountTransaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use function App\Helper\currencyFormat;

class GeneralJournalService
{
    public function data()
    {
        $generalJournal = AccountTransaction::with('account')
            ->where('transaction_type', 'TR')
            ->whereYear('date', Carbon::now())
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
                'date' => $item->date,
                'amount' => currencyFormat($item->amount),
                'type' => $item->entries_type,
                'account' => $item->account->code . ' ' . $item->account->name,
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
