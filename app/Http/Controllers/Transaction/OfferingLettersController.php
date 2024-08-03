<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use App\Http\Requests\Transaction\OfferingLetter\OfferingLetterRequest;
use App\Models\Branch;
use App\Models\Contact;
use App\Models\LetterHead;
use App\Models\OfferingLetter;
use App\Models\OfferingLetterProduct;
use App\Models\ServiceCategory;
use App\Service\OfferingLetterService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OfferingLettersController extends Controller
{
    public int $perPage = 10;
    protected OfferingLetterService $OfferingLetterService;
    private OfferingLetter $offeringLetter;
    private Contact $contact;
    private serviceCategory $serviceCategory;
    private OfferingLetterProduct $offeringLetterProduct;
    private Branch $branch;

    public function __construct()
    {
        $this->OfferingLetterService = new OfferingLetterService();
        $this->offeringLetter = new OfferingLetter();
        $this->contact = new Contact();
        $this->serviceCategory = new ServiceCategory();
        $this->offeringLetterProduct = new OfferingLetterProduct();
        $this->branch = new Branch();
    }

    public function index(): View
    {
        return view('pages.transaction.offering-letter.index');
    }


    public function data(): JsonResponse
    {
        $offeringLetters = $this->offeringLetter->getOfferingLettersBasedOnUserBranch($this->perPage);
        return response()->json($offeringLetters);
    }

    public function branchData(Request $request): JsonResponse
    {
        return response()->json($this->branch->getData($request));
    }

    public function filterByBranch(Branch $branch): JsonResponse
    {
        $filter = $this->offeringLetter->filteringDataBasedOnBranch($branch->id, $this->perPage);
        return response()->json($filter);
    }

    public function search(Request $request): JsonResponse
    {
        $query = $this->offeringLetter->searchOfferingLettersBasedOnUserBranch($request, $this->perPage);
        return response()->json($query);
    }


    public function create(): View
    {
        return view('pages.transaction.offering-letter.create');
    }

    public function getContactData(Request $request): JsonResponse
    {
        $contactData = $this->contact->getData($request);
        return response()->json($contactData);
    }

    public function getServicesCategoriesData(Request $request): JsonResponse
    {
        $servicesCategory = $this->serviceCategory->getData($request);
        return response()->json($servicesCategory);
    }


    public function store(OfferingLetterRequest $request): JsonResponse
    {
        $this->OfferingLetterService->store($request);
        return response()->json([
            'message' => 'data berhasil disimpan'
        ]);
    }


    public function show(OfferingLetter $offeringLetter): View
    {
        $offeringLetterServices = $this->offeringLetterProduct->getOfferingLetterProductServiceAttribute($offeringLetter->id);
        $letterHead = LetterHead::where('id', 1)->first();

        return view('pages.transaction.offering-letter.detail', compact(
            'offeringLetter',
            'letterHead',
            'offeringLetterServices'
        ));
    }


    public function viewFile(OfferingLetter $offeringLetter): View
    {
        return view('pages.transaction.offering-letter.view-file', compact('offeringLetter'));
    }

    public function edit(OfferingLetter $offeringLetter): View
    {
        return view('pages.transaction.offering-letter.edit', compact('offeringLetter'));
    }

    public function getSelectedContact(OfferingLetter $offeringLetter): JsonResponse
    {
        $selected = $this->contact->getSelectedData($offeringLetter->contact_id);
        return response()->json($selected);
    }


    public function getOfferingLetterProductServices(OfferingLetter $offeringLetter): JsonResponse
    {
        return response()->json(
            $this->offeringLetterProduct->getOfferingLetterProductServiceAttribute($offeringLetter->id)
        );
    }


    public function update(OfferingLetterRequest $request, OfferingLetter $offeringLetter): JsonResponse
    {
        $this->OfferingLetterService->update($request, $offeringLetter);
        return response()->json([
            'message' => 'data berhasil disimpan'
        ]);
    }


    public function confirm(OfferingLetter $offeringLetter): JsonResponse
    {
        $offeringLetter->update([
            'status' => 1
        ]);
        return response()->json([
            'message' => 'data berhasil di konfirmasi',
        ], 200);
    }

    public function destroy(OfferingLetter $offeringLetter): JsonResponse
    {
        $offeringLetter->delete();
        return response()->json([
            'message' => 'data berhasil di hapus',
        ]);
    }


    public function exportToPDF(OfferingLetter $offeringLetter): \Illuminate\Http\Response
    {
        $offeringLetterProduct = $this->offeringLetterProduct->getOfferingLetterProductServiceAttribute($offeringLetter->id);
        $pdf = Pdf::loadView('pages.transaction.offering-letter.export-pdf', compact('offeringLetter', 'offeringLetterProduct'))
            ->setPaper("A4", 'portrait');
        return $pdf->stream();
    }

}
