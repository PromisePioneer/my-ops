<?php

namespace App\Service;

use App\Models\OfferingLetter;
use App\Models\OfferingLetterProduct;
use App\Service\HelperService\HandleFileUploadService;
use Illuminate\Support\Facades\DB;

class OfferingLetterService
{
    private HandleFileUploadService $handleFileUploadService;

    public function __construct()
    {
        $this->handleFileUploadService = new HandleFileUploadService();
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
