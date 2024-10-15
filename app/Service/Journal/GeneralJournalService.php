<?php

namespace App\Service\Journal;

use App\Models\AccountTransaction;
use Carbon\Carbon;
use Illuminate\Http\Request;

class GeneralJournalService
{
    public function data(Request $request)
    {
        $generalJournal = AccountTransaction::with('account')
            ->where('transaction_type', 'TR')
            ->where('branch_id', $request->user()->branch_id)
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
                'date' => $item->date,
                'amount' => 'Rp.'.number_format($item->amount, 2),
                'type' => $item->entries_type,
                'account' => $item->account->code.' '.$item->account->name,
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
            $query->orWhere('branch_id', $branch);
        }

        if ($year) {
            $query->orWhereYear('date', $year);
        }

        if ($month) {
            $query->orWhereMonth('date', $month);
        }

        if ($year && $month) {
            $query->orWhereYear('date', $year)
                ->whereMonth('date', $month);
        }

        $data = $query->get();
        return self::formattedData($data);
    }

}