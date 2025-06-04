<?php

namespace App\Support\IncomeTransaction;

use App\Http\Requests\BaaRequest;
use App\Models\BAA;
use App\Models\Fab;
use App\Models\Master\Common\Contact;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use function App\Helper\convertToRoman;
use function App\Helper\formatDate;

class BAAService
{


    private static int $perPage = 10;


    private static function generateBaaNumber(Request $request): string
    {

        $fab = Fab::with('po')->where('id', $request->fab_id)->first();


        $baa = BAA::latest()->first();

        $companyCode = Contact::where('id', $fab->po->contact->id)->first()->company_code;
        $month = convertToRoman(Carbon::parse($request->date)->format('m'));
        $year = Carbon::parse($request->date)->format('Y');


        if ($baa) {
            $convertInvNumberToArray = explode('/', $baa->baa_number);
            $startingNumber = $convertInvNumberToArray[0];
            $startValue = str_pad((int)$startingNumber + 1, 3, '0', STR_PAD_LEFT);

            return $startValue . '/' . 'BAA/' . 'MYT-' . $companyCode . '/' . $month . '/' . $year;
        }

        $startingNumber = '000';
        $startValue = str_pad((int)$startingNumber + 1, 3, '0', STR_PAD_LEFT);

        return $startValue . '/' . 'BAA/' . 'MYT-' . $companyCode . '/' . $month . '/' . $year;
    }


    public function data(): LengthAwarePaginator
    {
        $data = BAA::orderBy('created_at', 'desc')->paginate(self::$perPage);


        return self::formattedData($data);
    }


    public function search(Request $request): LengthAwarePaginator
    {

        $search = $request->input('search');

        $data = BAA::orderBy('created_at', 'desc')
            ->when(!empty($search), function ($query) use ($search) {
                $query->where('baa_number', 'like', '%' . $search . '%')
                    ->orWhere('po_number', 'like', '%' . $search . '%');
            })->paginate(self::$perPage);

        return self::formattedData($data);
    }

    private static function formattedData(LengthAwarePaginator $data): LengthAwarePaginator
    {
        $baa = $data->getCollection()->map(function ($item) {
            return [
                'id' => $item->id,
                'baa_number' => $item->baa_number,
                'date' => formatDate($item->date),
                'po_number' => $item->po_number,
                'work_location' => $item->work_location,
                'status' => $item->status,
            ];
        });


        $data->setCollection($baa);
        return $data;
    }

    public function store(BaaRequest $request): void
    {
        $data = $request->validated();
        $data['baa_number'] = self::generateBaaNumber($request);
        BAA::create($data);
    }


    public function update(BaaRequest $request, BAA $baa): void
    {
        $data = $request->validated();
        $data['baa_number'] = self::generateBaaNumber($request);
        $baa->update($data);
    }


    public function getBAA(Request $request)
    {
        $search = $request->input('search');
        $baa = BAA::search($search)->query(function ($query) {
            $query->orderBy('created_at', 'asc');
        })->get();

        return $baa->map(function ($item) {
            return [
                'id' => $item->id,
                'text' => $item->baa_number
            ];
        });
    }


    public function selectedBAA(BAA $baa): array
    {
        return [
            'id' => $baa->id,
            'text' => $baa->baa_number
        ];
    }


}
