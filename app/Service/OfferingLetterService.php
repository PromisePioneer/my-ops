<?php

namespace App\Service;

use App\Models\Contact;
use App\Models\OfferingLetter;
use App\Models\OfferingLetterProduct;
use App\Models\OfferingLetterServiceDescription;
use App\Service\HelperService\HandleFileUploadService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Throwable;

use function App\Helper\convertToRoman;

class OfferingLetterService
{
    private static int $perPage = 10;
    private HandleFileUploadService $handleFileUploadService;

    public function __construct()
    {
        $this->handleFileUploadService = new HandleFileUploadService();
    }


    public function generateOfferingNumber(Request $request): string
    {
        $invoice = OfferingLetter::where('branch_id', $request->user()->branch_id)->where(
            'contact_id',
            $request->contact_id
        )->latest()->first();
        $findCompanyName = Contact::where('id', $request->contact_id)->first()->company_name;
        $invoiceDate = convertToRoman(Carbon::parse($request->due_date)->format('m'));
        $invoiceYear = convertToRoman(Carbon::parse($request->due_date)->format('Y'));

        $abbr = explode(' ', $findCompanyName);


        array_shift($abbr);


        $acronym = '';

        foreach ($abbr as $value) {
            $acronym .= mb_substr($value, 0, 1);
        }

        if ($invoice) {
            $convertInvNumberToArray = explode('/', $invoice->invoice_number);
            $startingNumber = $convertInvNumberToArray[0];
            $startValue = str_pad((int)$startingNumber + 1, 3, '0', STR_PAD_LEFT);

            return $startValue.'/'.'SPH/'.'MYT-'.$acronym.'/'.$invoiceDate.'/'.$invoiceYear;
        }

        $startingNumber = '000';
        $startValue = str_pad((int)$startingNumber + 1, 3, '0', STR_PAD_LEFT);

        return $startValue.'/'.'SPH/'.'MYT-'.$acronym.'/'.$invoiceDate.'/'.$invoiceYear;
    }

    public function data(Request $request): LengthAwarePaginator
    {
        $offeringLetters = OfferingLetter::with('contact', 'user', 'branch')
            ->where('branch_id', $request->user()->branch_id)
            ->select(
                'id',
                'offering_number',
                'status',
                'created_at',
                'pic',
                'contact_id',
                'branch_id'
            )
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
        $offeringLetter = OfferingLetter::where('offering_number', 'like', '%'.$search.'%')
            ->orWhereHas('contact', function ($query) use ($request, $search) {
                $query->where('full_name', 'like', '%'.$search.'%');
                $query->orWhere('company_name', 'like', '%'.$search.'%');
            })
            ->orWhere('date', 'like', '%'.$search.'%')
            ->orWhere('attachment', 'like', '%'.$search.'%')
            ->orWhere('foreword', 'like', '%'.$search.'%')
            ->orWhere('notes', 'like', '%'.$search.'%')
            ->orWhere('marketing_agent_name', 'like', '%'.$search.'%')
            ->orWhere('marketing_agent_contact', 'like', '%'.$search.'%')
            ->where('branch_id', Auth::user()->branch_id)
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
            $this->offeringLetterServiceDescriptionStore($request, $offeringLetter);
        });
    }

    public function offeringLetterServiceDescriptionStore($request, $offeringLetter): void
    {
        foreach ($request['serviceDescription'] as $key => $value) {
            $value['offering_letter_id'] = $offeringLetter->id;
            OfferingLetterServiceDescription::create($value);
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

    public function update($request, $offeringLetter): void
    {
        DB::transaction(function () use ($request, $offeringLetter) {
            $data = $request->validated();
            $data['file'] = $this->handleFileUploadService->upload(
                $request,
                'documents/offering-letters',
                'file',
                $offeringLetter->file
            );
            $data['branch_id'] = $request->user()->branch_id;
            $offeringLetter->update($data);
            OfferingLetterProduct::whereIn('offering_letter_id', [$offeringLetter->id])->delete();
            OfferingLetterServiceDescription::whereIn('offering_letter_id', [$offeringLetter->id])->delete();
            $this->offeringProductServiceStore($request, $offeringLetter);
            $this->offeringLetterServiceDescriptionStore($request, $offeringLetter);
        });
    }
}
