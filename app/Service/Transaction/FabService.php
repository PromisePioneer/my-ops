<?php

namespace App\Service\Transaction;

use App\Models\Contact;
use App\Models\Fab;
use App\Models\FabHasSKL;
use App\Models\FabServiceCategory;
use App\Service\CompanyNameService;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;
use function App\Helper\convertToRoman;
use function App\Helper\formatDate;

class FabService
{
    private static int $perPage = 10;
    private CompanyNameService $companyNameService;


    public function __construct()
    {
        $this->companyNameService = new CompanyNameService();
    }

    private static function generateFABNumber(Request $request): string
    {
        $fab = Fab::where('contact_id', $request->contact_id)
            ->latest()
            ->first();

        $companyCode = Contact::where('id', $request->contact_id)->first()->company_code;
        $month = convertToRoman(Carbon::parse($request->due_date)->format('m'));
        $year = Carbon::parse($request->due_date)->format('Y');


        if ($fab) {
            $convertInvNumberToArray = explode('/', $fab->fab_number);
            $startingNumber = $convertInvNumberToArray[0];
            $startValue = str_pad((int)$startingNumber + 1, 3, '0', STR_PAD_LEFT);

            return $startValue . '/' . 'SPH/' . 'MYT-' . $companyCode . '/' . $month . '/' . $year;
        }

        $startingNumber = '000';
        $startValue = str_pad((int)$startingNumber + 1, 3, '0', STR_PAD_LEFT);

        return $startValue . '/' . 'SPH/' . 'MYT-' . $companyCode . '/' . $month . '/' . $year;
    }


    private static function generateContractNumber(Request $request): string
    {
        $fab = Fab::where('contact_id', $request->contact_id)
            ->latest()
            ->first();

        $companyCode = Contact::where('id', $request->contact_id)->first()->company_code;
        $month = convertToRoman(Carbon::parse($request->due_date)->format('m'));
        $year = Carbon::parse($request->due_date)->format('Y');


        if ($fab) {
            $convertInvNumberToArray = explode('/', $fab->fab_number);
            $startingNumber = $convertInvNumberToArray[0];
            $startValue = str_pad((int)$startingNumber + 1, 3, '0', STR_PAD_LEFT);

            return $startValue . '/' . 'PKS/' . 'MYT-' . $companyCode . '/' . $month . '/' . $year;
        }

        $startingNumber = '000';
        $startValue = str_pad((int)$startingNumber + 1, 3, '0', STR_PAD_LEFT);

        return $startValue . '/' . 'PKS/' . 'MYT-' . $companyCode . '/' . $month . '/' . $year;
    }


    public function data(): LengthAwarePaginator
    {
        $data = Fab::with('contact', 'user')
            ->paginate(self::$perPage);
        return self::formattedData($data);
    }

    public function formattedData(LengthAwarePaginator $fabData): LengthAwarePaginator
    {
        $data = $fabData->getCollection()->map(function ($item) {
            return [
                'date' => formatDate($item->date),
                'id' => $item->id,
                'code' => $item->fab_number,
                'contact' => $item->contact->company_name . '-' . $item->contact->pic_name,
                'created_by' => $item->user->name,
            ];
        });

        $fabData->setCollection($data);
        return $fabData;
    }

    public function filterByBranch(int $branchId): LengthAwarePaginator
    {
        return Fab::where('branch_id', $branchId)->paginate(self::$perPage);
    }

    public function search(Request $request): LengthAwarePaginator
    {
        $search = $request->input('search');
        $query = Fab::with('contact', 'branch')
            ->where('subscription_status', 'like', '%' . $search . '%')
            ->whereHas('branch', function ($query) use ($request, $search) {
                $query->where('name', 'like', '%' . $search . '%');
            })->orWhereHas('contact', function ($query) use ($request, $search) {
                $query->where('full_name', 'like', '%' . $search . '%');
                $query->where('company_name', 'like', '%' . $search . '%');
            })->paginate(self::$perPage);

        return self::formattedData($query);
    }


    /**
     * @throws Throwable
     */
    public function store($request): void
    {
        DB::transaction(function () use ($request) {
            $data = $request->validated();
            $data['created_by'] = $request->user()->id;
            $data['fab_number'] = self::generateFABNumber($request);
            $data['offering_letter_id'] = $request->offering_letter_id;
            $data['contract_number'] = self::generateContractNumber($request);
            $fab = Fab::create($data);
            $this->fabHasServiceCategoriesStoreOrUpdate($request, $fab);
            $this->fabHasSKLStoreOrUpdate($request, $fab);
        });
    }


    public function update($request, $fab): void
    {
        DB::transaction(function () use ($request, $fab) {
            $data = $request->validated();
            $data['created_by'] = $request->user()->id;
            $data['fab_number'] = self::generateFABNumber($request);
            $data['offering_letter_id'] = $request->offering_letter_id;
            $fab->update($data);
            FabServiceCategory::whereIn('fab_id', [$fab->id])->delete();
            FabHasSKL::whereIn('fab_id', [$fab->id])->delete();
            $this->fabHasServiceCategoriesStoreOrUpdate($request, $fab);
            $this->fabHasSKLStoreOrUpdate($request, $fab);
        });
    }

    private function fabHasServiceCategoriesStoreOrUpdate($request, $fab): void
    {
        foreach ($request['fabServices'] as $key => $value) {
            $value['fab_id'] = $fab->id;
            FabServiceCategory::create($value);
        }
    }

    private function fabHasSKLStoreOrUpdate($request, $fab): void
    {

        if (empty($request['skl'])) return;
        foreach ($request['skl'] as $key => $value) {
            $value['fab_id'] = $fab->id;
            FabHasSKL::create($value);
        }
    }


    public function confirm($fab): void
    {
        $fab->status = true;
        $fab->save();
    }


    public function convertCompanyNameToCapitalLetter(Fab $fab): string
    {
        return $this->companyNameService->convertCompanyNameToCapitalLetter($fab->contact->company_name);
    }

}
