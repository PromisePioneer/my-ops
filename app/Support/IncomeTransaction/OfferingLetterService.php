<?php

namespace App\Support\IncomeTransaction;

use App\Models\Master\Common\Contact;
use App\Models\OfferingLetter;
use App\Models\OfferingLetterProduct;
use App\Models\OfferingLetterServiceDescription;
use App\Models\TaxSetting;
use App\Support\HelperService\CompanyNameService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Throwable;
use function App\Helper\convertToRoman;

class OfferingLetterService
{
    private static int $perPage = 10;
    private CompanyNameService $companyNameService;

    public function __construct()
    {
        $this->companyNameService = new CompanyNameService();
    }

    public function generateOfferingNumber(Request $request): string
    {
        $offeringLetter = OfferingLetter::where('branch_id', $request->user()->branch_id)->where(
            'contact_id',
            $request->contact_id
        )->latest()->first();

        $companyCode = Contact::where('id', $request->contact_id)->first()->company_code;
        $month = convertToRoman(Carbon::parse($request->due_date)->format('m'));
        $year = Carbon::parse($request->due_date)->format('Y');

        if ($offeringLetter) {
            $convertInvNumberToArray = explode('/', $offeringLetter->offering_number);
            $startingNumber = $convertInvNumberToArray[0];
            $startValue = str_pad((int)$startingNumber + 1, 3, '0', STR_PAD_LEFT);

            return $startValue . '/' . 'SPH/' . 'MYT-' . $companyCode . '/' . $month . '/' . $year;
        }

        $startingNumber = '000';
        $startValue = str_pad((int)$startingNumber + 1, 3, '0', STR_PAD_LEFT);

        return $startValue . '/' . 'SPH/' . 'MYT-' . $companyCode . '/' . $month . '/' . $year;
    }

    public function data(Request $request): LengthAwarePaginator
    {
        $offeringLetters = OfferingLetter::with('contact', 'user', 'branch')
            ->where('branch_id', $request->user()->branch_id)
            ->paginate(self::$perPage);
        return self::formatOfferingLettersData($offeringLetters);
    }

    private static function formatOfferingLettersData(LengthAwarePaginator $offeringLetter): LengthAwarePaginator
    {
        $formattedData = $offeringLetter->getCollection()->map(function ($offeringLetter) {
            return [
                'id' => $offeringLetter->id,
                'offering_number' => $offeringLetter->offering_number,
                'status' => $offeringLetter->status,
                'created_at' => $offeringLetter->created_at,
                'created_by' => $offeringLetter->user->name,
                'company_name' => $offeringLetter->contact->company_name,
                'branch' => $offeringLetter->branch?->name,
            ];
        });
        $offeringLetter->setCollection($formattedData);
        return $offeringLetter;
    }

    public function search(Request $request): LengthAwarePaginator
    {
        $search = $request->input('search');
        $offeringLetter = OfferingLetter::with('contact')
            ->where('offering_number', 'like', '%' . $search . '%')
            ->orWhereHas('contact', function ($query) use ($request, $search) {
                $query->where('pic_name', 'like', '%' . $search . '%');
                $query->orWhere('company_name', 'like', '%' . $search . '%');
            })
            ->paginate(self::$perPage);

        return self::formatOfferingLettersData($offeringLetter);
    }

    /**
     * @throws Throwable
     */
    public function store($request): void
    {
        DB::transaction(function () use ($request) {
            $data = $request->validated();
            $data['offering_number'] = self::generateOfferingNumber($request);
            $data['branch_id'] = $request->user()->branch_id;
            $offeringLetter = OfferingLetter::create($data);
            $this->offeringProductServiceStore($request, $offeringLetter);
            $this->offeringLetterSKLStoreOrUpdate($request, $offeringLetter);
        });
    }

    public function offeringLetterSKLStoreOrUpdate($request, $offeringLetter): void
    {
        foreach ($request['serviceDescription'] as $key => $value) {
            if ($value['skl_id']) {
                $value['offering_letter_id'] = $offeringLetter->id;
                OfferingLetterServiceDescription::create($value);
            }
        }
    }

    public function offeringProductServiceStore($request, $offeringLetter): void
    {
        foreach ($request['data'] as $key => $value) {
            $value['offering_letter_id'] = $offeringLetter->id;
            OfferingLetterProduct::create($value);
        }
    }

    public function filterByBranch(int $branchId): LengthAwarePaginator
    {
        $query = OfferingLetter::where('branch_id', $branchId)->paginate(self::$perPage);
        return self::formatOfferingLettersData($query);
    }

    /**
     * @throws Throwable
     */
    public function update($request, $offeringLetter): void
    {
        DB::transaction(function () use ($request, $offeringLetter) {
            $data = $request->validated();
            $data['offering_number'] = self::generateOfferingNumber($request);
            $data['branch_id'] = $request->user()->branch_id;
            $offeringLetter->update($data);
            OfferingLetterProduct::whereIn('offering_letter_id', [$offeringLetter->id])->delete();
            OfferingLetterServiceDescription::whereIn('offering_letter_id', [$offeringLetter->id])->delete();
            $this->offeringProductServiceStore($request, $offeringLetter);
            $this->offeringLetterSKLStoreOrUpdate($request, $offeringLetter);
        });
    }


    public function convertCompanyNameToCapitalLetter(OfferingLetter $offeringLetter): string
    {
        return $this->companyNameService->convertCompanyNameToCapitalLetter($offeringLetter->contact->company_name);
    }


    public function getPPNRate(Collection $offeringLetterProduct)
    {
        $getPPN = TaxSetting::where('name', 'PPN')->first();
        return $getPPN->rate / 100 * $offeringLetterProduct->sum('price');

    }
}
