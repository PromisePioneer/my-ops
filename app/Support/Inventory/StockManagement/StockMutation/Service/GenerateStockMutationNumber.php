<?php

namespace App\Support\Inventory\StockManagement\StockMutation\Service;

use App\Models\Invoice;
use App\Models\Master\Common\Branch;
use App\Models\Master\Common\Contact;
use App\Models\StockMutation;
use Carbon\Carbon;
use function App\Helper\convertToRoman;

class GenerateStockMutationNumber
{
    public static function apply($senderBranchId, $date)
    {
        $branch = Branch::find($senderBranchId);
        $latestData = StockMutation::where('old_branch_id', $senderBranchId)
            ->latest()
            ->first();
        $month = convertToRoman(Carbon::parse($date)->format('m'));
        $year = Carbon::parse($date)->format('Y');


        if ($latestData) {
            $convertInvNumberToArray = explode('/', $latestData->stock_mutation_number);
            $startingNumber = $convertInvNumberToArray[0];
            $startValue = str_pad((int)$startingNumber + 1, 3, '0', STR_PAD_LEFT);

            return $startValue . '/' . 'BAST/' . 'MY-' . $branch->code . '/' . $month . '/' . $year;
        }

        $startingNumber = '000';
        $startValue = str_pad((int)$startingNumber + 1, 3, '0', STR_PAD_LEFT);

        return $startValue . '/' . 'BAST/' . 'MY-' . $branch->code . '/' . $month . '/' . $year;
    }
}
