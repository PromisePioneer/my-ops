<?php

namespace App\Service\Transaction;

use App\Models\BAA;
use App\Models\Bast;
use App\Models\Contact;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Throwable;
use function App\Helper\convertToRoman;
use function App\Helper\formatDate;

class BastService
{
    private static int $perPage = 10;

    public function generateBastNumber(Request $request)
    {
        $baa = BAA::with('fab')->where('id', $request->baa_id)->first();
        $bast = Bast::latest()->first();

        $companyCode = Contact::where('id', $baa->fab->po->contact->id)->first()->company_code;
        $month = convertToRoman(Carbon::parse($request->date)->format('m'));
        $year = Carbon::parse($request->date)->format('Y');

        if ($bast) {
            $convertInvNumberToArray = explode('/', $bast->bast_number);
            $startingNumber = $convertInvNumberToArray[0];
            $startValue = str_pad((int)$startingNumber + 1, 3, '0', STR_PAD_LEFT);

            return $startValue . '/' . 'BAST/' . 'MYT-' . $companyCode . '/' . $month . '/' . $year;
        }

        $startingNumber = '000';
        $startValue = str_pad((int)$startingNumber + 1, 3, '0', STR_PAD_LEFT);

        return $startValue . '/' . 'BAST/' . 'MYT-' . $companyCode . '/' . $month . '/' . $year;
    }

    public function data(): LengthAwarePaginator
    {
        $data = Bast::with('baa')->paginate(self::$perPage);
        return self::formattedData($data);
    }


    public function formattedData(LengthAwarePaginator $bastData): LengthAwarePaginator
    {
        $data = $bastData->getCollection()->map(function ($item) {
            return [
                'id' => $item->id,
                'date' => formatDate($item->date),
                'bast_number' => $item->bast_number,
                'contact' => $item->baa->fab->po->contact->pic_name . ' - ' . $item->baa->fab->po->contact->company_name,
                'status' => $item->status,
            ];
        });

        $bastData->setCollection($data);
        return $bastData;
    }


    public function search(Request $request): LengthAwarePaginator
    {
        $search = $request->input('search');
        $query = Bast::with('contact', 'user')->where('branch_id', $request->user()->branch_id);
        if (!empty($search)) {
            $query->where('bast_number', 'like', '%'.$search.'%')
                ->where('branch_id', $request->user()->branch_id)
                ->orWhereHas('contact', function ($query) use ($search) {
                    $query->where('full_name', 'like', '%'.$search.'%');
                    $query->orWhere('company_name', 'like', '%'.$search.'%');
                })
                ->orWhere('date', 'like', '%'.$search.'%')
                ->orWhere('first_party_identity_name', 'like', '%'.$search.'%')
                ->orWhere('first_party_position', 'like', '%'.$search.'%')
                ->orWhere('objective', 'like', '%'.$search.'%')
                ->orWhere('file', 'like', '%'.$search.'%')
                ->orWhere('status', 'like', '%'.$search.'%');
        }


        $data = $query->paginate(self::$perPage);
        return self::formattedData($data);
    }


    /**
     * @throws Throwable
     */
    public function store($request): void
    {
        $data = $request->validated();
        $data['bast_number'] = self::generateBastNumber($request);
        Bast::create($data);
    }
    /**
     * @throws Throwable
     */
    public function update($request, $bast): void
    {
        $data = $request->validated();
        $data['bast_number'] = self::generateBastNumber($request);
        $bast->update($data);
    }
}
