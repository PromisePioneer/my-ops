<?php

namespace App\Support;

use App\Models\Fab;
use App\Models\Master\Common\Contact;
use App\Models\SPK;
use Carbon\Carbon;
use function App\Helper\convertToRoman;

class SPKService
{
    public function generateSpkNumber($fabId): string
    {

        $fab = Fab::with('po')->where('id', $fabId)->first();
        $baa = SPK::latest()->first();

        $companyCode = Contact::where('id', $fab->po->contact->id)->first()->company_code;
        $month = convertToRoman(Carbon::parse(Carbon::now())->format('m'));
        $year = Carbon::parse(Carbon::now())->format('Y');


        if ($baa) {
            $convertInvNumberToArray = explode('/', $baa->baa_number);
            $startingNumber = $convertInvNumberToArray[0];
            $startValue = str_pad((int)$startingNumber + 1, 3, '0', STR_PAD_LEFT);

            return $startValue . '/' . 'SPK/' . 'MYT-' . $companyCode . '/' . $month . '/' . $year;
        }

        $startingNumber = '000';
        $startValue = str_pad((int)$startingNumber + 1, 3, '0', STR_PAD_LEFT);

        return $startValue . '/' . 'SPK/' . 'MYT-' . $companyCode . '/' . $month . '/' . $year;
    }
}
