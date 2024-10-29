<?php

namespace App\Http\Controllers\Accounting\Transaction;

use App\Http\Controllers\Controller;
use App\Http\Requests\Transaction\OfferingLetter\OfferingLetterRequest;
use App\Models\Boq;
use App\Models\Branch;
use App\Models\Contact;
use App\Models\LetterHead;
use App\Models\OfferingLetter;
use App\Models\OfferingLetterProduct;
use App\Models\OfferingLetterServiceDescription;
use App\Models\OfferingLetterSKL;
use App\Models\ServiceCategory;
use App\Models\TaxSetting;
use App\Models\UnitType;
use App\Models\User;
use App\Service\OfferingLetterService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class OfferingLettersController extends Controller
{
    public int $perPage = 10;

    protected OfferingLetterService $OfferingLetterService;

    private Contact $contact;
    private serviceCategory $serviceCategory;
    private Branch $branch;
    private OfferingLetterService $offeringLetterService;
    private User $user;
    private UnitType $unitType;
    private OfferingLetterSKL $offeringLetterSKL;

    public function __construct()
    {
        $this->offeringLetterService = new OfferingLetterService();
        $this->contact = new Contact();
        $this->serviceCategory = new ServiceCategory();
        $this->branch = new Branch();
        $this->user = new  User();
        $this->unitType = new UnitType();
        $this->offeringLetterSKL = new OfferingLetterSKL();
    }

    public function index(): View
    {
        return view('pages.transaction.offering-letter.index');
    }

    public function data(Request $request): JsonResponse
    {
        return response()->json($this->offeringLetterService->data($request));
    }

    public function branchData(Request $request): JsonResponse
    {
        return response()->json($this->branch->getData($request));
    }

    public function filterByBranch(Branch $branch): JsonResponse
    {
        $filter = $this->offeringLetterService->filterByBranch($branch->id);

        return response()->json($filter);
    }


    public function search(Request $request): JsonResponse
    {
        return response()->json($this->offeringLetterService->search($request));
    }

    public function create(): View
    {
        return view('pages.transaction.offering-letter.create');
    }

    public function getContactData(Request $request): JsonResponse
    {
        return response()->json($this->contact->getData($request));
    }


    public function getUserData(Request $request): JsonResponse
    {
        return response()->json($this->user->getUser($request));
    }

    public function getServicesCategoriesData(Request $request): JsonResponse
    {
        return response()->json($this->serviceCategory->getData($request));
    }

    public function getOfferingLettersProduct(OfferingLetter $offeringLetter): JsonResponse
    {
        $offeringLetterProduct = OfferingLetterProduct::where('offering_letter_id', $offeringLetter->id)->get();
        return response()->json($offeringLetterProduct);
    }

    public function getOfferingLetterDescription(OfferingLetter $offeringLetter): JsonResponse
    {
        $data = OfferingLetterServiceDescription::with('skl')->where('offering_letter_id', $offeringLetter->id)->get();
        return response()->json($data);
    }

    public function selectedUser(OfferingLetter $offeringLetter): JsonResponse
    {
        return response()->json($this->user->getSelectedData($offeringLetter->pic));
    }

    public function getUnitType(Request $request): JsonResponse
    {
        return response()->json($this->unitType->getData($request));
    }

    public function getSKL(Request $request): JsonResponse
    {
        return response()->json($this->offeringLetterSKL->getData($request));
    }

    public function getSelectedSKL(OfferingLetterServiceDescription $offeringLetterServiceDescription): JsonResponse
    {
        return response()->json($this->offeringLetterSKL->getSelectedData($offeringLetterServiceDescription->skl_id));
    }

    public function store(OfferingLetterRequest $request): JsonResponse
    {
        $this->offeringLetterService->store($request);

        return response()->json([
            'message' => 'data berhasil disimpan',
        ]);
    }

    public function show(OfferingLetter $offeringLetter): View
    {
        $offeringLetterServices = OfferingLetterProduct::with('unitType')->where('offering_letter_id', $offeringLetter->id)->get();
        $offeringLetterServiceDescription = OfferingLetterServiceDescription::with('skl')
            ->where('offering_letter_id', $offeringLetter->id)
            ->get();

        $getPPN = TaxSetting::where('name', 'PPN')->first();

        $totalPPN = $getPPN->rate / 100 * $offeringLetterServices->sum('price');
        $total = $offeringLetterServices->sum('price') + $totalPPN;

        return view(
            'pages.transaction.offering-letter.detail',
            compact(
                'offeringLetter',
                'offeringLetterServices',
                'totalPPN',
                'total',
                'offeringLetterServiceDescription'
            )
        );
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


    public function getSelectedUnitType(OfferingLetterProduct $offeringLetterProduct): JsonResponse
    {
        dd($offeringLetterProduct);
        return response()->json($this->unitType->getSelectedData($offeringLetterProduct->unit_type_id));
    }


    public function getOfferingLetterProductServices(OfferingLetter $offeringLetter): JsonResponse
    {
        $data = OfferingLetterProduct::with('offeringLetter', 'serviceCategory')
            ->where('offering_letter_id', $offeringLetter->id)
            ->get();
        return response()->json($data);
    }

    public function confirm(OfferingLetter $offeringLetter): JsonResponse
    {
        $offeringLetter->update([
            'status' => 1,
        ]);

        return response()->json([
            'message' => 'data berhasil di konfirmasi',
        ], 200);
    }

    public function update(OfferingLetterRequest $request, OfferingLetter $offeringLetter): JsonResponse
    {
        $this->offeringLetterService->update($request, $offeringLetter);

        return response()->json([
            'message' => 'data berhasil disimpan',
        ]);
    }

    public function destroy(OfferingLetter $offeringLetter): JsonResponse
    {
        $offeringLetter->delete();

        return response()->json([
            'message' => 'data berhasil di hapus',
        ]);
    }

    public function exportToPDF(OfferingLetter $offeringLetter): Response
    {
        $data = $offeringLetter->with('user', 'user.roles')->first();
        $offeringLetterServices = OfferingLetterProduct::with('unitType')->where('offering_letter_id', $offeringLetter->id)->get();
        $offeringLetterServiceDescription = OfferingLetterServiceDescription::with('skl')
            ->where('offering_letter_id', $offeringLetter->id)
            ->get();
        $getPPN = TaxSetting::where('name', 'PPN')->first();

        $totalPPN = $getPPN->rate / 100 * $offeringLetterServices->sum('price');
        $total = $offeringLetterServices->sum('price') + $totalPPN;

        $pdf = Pdf::loadView(
            'pages.transaction.offering-letter.export-pdf',
            compact(
                'data',
                'offeringLetterServices',
                'offeringLetterServiceDescription',
                'total',
                'getPPN',
                'totalPPN'
            )
        )->setPaper('A4', 'portrait');

        return $pdf->stream();
    }


}
