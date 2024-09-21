<?php

namespace App\Service;

use App\Models\OfferingLetter;
use App\Models\OfferingLetterProduct;
use App\Service\HelperService\HandleFileUploadService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OfferingLetterService
{
    private static int $perPage = 10;
    private HandleFileUploadService $handleFileUploadService;

    public function __construct()
    {
        $this->handleFileUploadService = new HandleFileUploadService();
    }

    public function data(Request $request): LengthAwarePaginator
    {
        $offeringLetters = OfferingLetter::with('contact', 'user', 'branch')
            ->where('branch_id', $request->user()->branch_id)
            ->select('id', 'offering_number', 'status', 'created_at', 'created_by', 'contact_id', 'branch_id')
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

    public function store($request): void
    {
        DB::transaction(function () use ($request) {
            $data = $request->validated();
            $data['file'] = $this->handleFileUploadService->upload($request, 'documents/offering-letters', 'file');
            $data['created_by'] = $request->user()->id;
            $data['branch_id'] = $request->user()->branch_id;
            $offeringLetter = OfferingLetter::create($data);
            $this->offeringProductServiceStore($request, $offeringLetter);
        });
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
            $data['created_by'] = $request->user()->id;
            $data['branch_id'] = $request->user()->branch_id;
            $offeringLetter->update($data);
            OfferingLetterProduct::whereIn('offering_letter_id', [$offeringLetter->id])->delete();
            $this->offeringProductServiceStore($request, $offeringLetter);
        });
    }
}
