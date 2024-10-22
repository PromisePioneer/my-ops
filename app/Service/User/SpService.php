<?php

namespace App\Service\User;

use App\Http\Requests\ADMS\AttendancesSummaryAssignSPRequest;
use App\Http\Requests\SPRequest;
use App\Models\SP;
use Carbon\Carbon;

use function App\Helper\convertToRoman;

class SpService
{
    public function generateSpNumber(SPRequest|AttendancesSummaryAssignSPRequest $request): string
    {
        $sp = SP::latest()->first();
        $spMonth = convertToRoman(Carbon::parse($request->due_date)->format('m'));
        $spYear = Carbon::parse($request->due_date)->format('Y');

        if ($sp) {
            $convertInvNumberToArray = explode('/', $sp->sp_number);
            $startingNumber = $convertInvNumberToArray[0];
            $startValue = str_pad((int)$startingNumber + 1, 3, '0', STR_PAD_LEFT);

            return $startValue.'/MY-SP/'.$spMonth.'/'.$spYear;
        }

        $startingNumber = '001';
        $startValue = str_pad((int)$startingNumber, 3, '0', STR_PAD_LEFT);



        return $startValue.'/MY-SP/'.$spMonth.'/'.$spYear;
    }
}
