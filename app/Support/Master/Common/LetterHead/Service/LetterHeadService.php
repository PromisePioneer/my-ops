<?php

namespace App\Support\Master\Common\LetterHead\Service;

use AllowDynamicProperties;
use App\Http\Requests\LetterHeadRequest;
use App\Models\LetterHead;
use App\Support\HelperService\HandleFileUploadService;
use App\Support\Master\Common\LetterHead\Repository\LetterHeadRepository;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

#[AllowDynamicProperties] class LetterHeadService
{


    private static int $perPage = 10;


    public function __construct()
    {
        $this->letterHead = new LetterHead();
        $this->letterHeadRepository = new LetterHeadRepository();
        $this->handleFileUploadService = new HandleFileUploadService();
    }

    public function data(Request $request): LengthAwarePaginator
    {
        return $this->letterHeadRepository->data($request)->paginate(self::$perPage);
    }


    public function store(LetterHeadRequest $request): void
    {
        LetterHead::create([
            'company_id' => $request->session()->get('company_session'),
            'header' => $this->handleFileUploadService->upload(
                $request,
                'documents/letterhead/header/',
                'header',
            ),
            'footer' => $this->handleFileUploadService->upload(
                $request,
                'documents/letterhead/footer/',
                'footer',
            ),
        ]);
    }


    public function update(LetterHeadRequest $request, LetterHead $letterHead): void
    {
        LetterHead::where('id', $letterHead->id)->update([
            'header' => $this->handleFileUploadService->upload(
                $request,
                'documents/letterhead/header/',
                'header',
                $letterHead->header
            ),
            'footer' => $this->handleFileUploadService->upload(
                $request,
                'documents/letterhead/footer/',
                'footer',
                $letterHead->footer
            ),
        ]);
    }


}
