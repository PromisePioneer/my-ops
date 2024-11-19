<?php

namespace App\Http\Controllers\Accounting\Transaction;

use App\Http\Controllers\Controller;
use App\Http\Requests\Transaction\OfferingLetter\OfferingLetterRequest;
use App\Models\Contact;
use App\Models\OfferingLetter;
use App\Models\OfferingLetterProduct;
use App\Models\OfferingLetterServiceDescription;
use App\Models\ServiceCategory;
use App\Models\SKL;
use App\Models\TaxSetting;
use App\Models\UnitType;
use App\Models\User;
use App\Service\OfferingLetterService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Spatie\Browsershot\Browsershot;

class OfferingLettersController extends Controller
{
    public int $perPage = 10;
    protected OfferingLetterService $OfferingLetterService;
    private Contact $contact;
    private serviceCategory $serviceCategory;
    private OfferingLetterService $offeringLetterService;
    private User $user;
    private UnitType $unitType;
    private SKL $offeringLetterSKL;

    public function __construct()
    {
        $this->offeringLetterService = new OfferingLetterService();
        $this->contact = new Contact();
        $this->serviceCategory = new ServiceCategory();
        $this->user = new  User();
        $this->unitType = new UnitType();
        $this->offeringLetterSKL = new SKL();
    }

    /**
     * @throws AuthorizationException
     */
    public function index(): View
    {
        $this->authorize('viewAny', OfferingLetter::class);
        return view('pages.transaction.offering-letter.index');
    }

    /**
     * @throws AuthorizationException
     */
    public function data(Request $request): JsonResponse
    {
        $this->authorize('view', OfferingLetter::class);
        return response()->json($this->offeringLetterService->data($request));
    }


    /**
     * @throws AuthorizationException
     */
    public function search(Request $request): JsonResponse
    {
        $this->authorize('view', OfferingLetter::class);
        return response()->json($this->offeringLetterService->search($request));
    }

    /**
     * @throws AuthorizationException
     */
    public function create(): View
    {
        $this->authorize('create', OfferingLetter::class);
        return view('pages.transaction.offering-letter.create');
    }

    /**
     * @throws AuthorizationException
     */
    public function getContactData(Request $request): JsonResponse
    {
        $this->authorize('create', OfferingLetter::class);
        $this->authorize('update', OfferingLetter::class);
        return response()->json($this->contact->getData($request));
    }


    /**
     * @throws AuthorizationException
     */
    public function getUserData(Request $request): JsonResponse
    {
        $this->authorize('create', OfferingLetter::class);
        $this->authorize('update', OfferingLetter::class);
        return response()->json($this->user->getUser($request));
    }

    /**
     * @throws AuthorizationException
     */
    public function getServicesCategoriesData(Request $request): JsonResponse
    {
        $this->authorize('create', OfferingLetter::class);
        $this->authorize('update', OfferingLetter::class);
        return response()->json($this->serviceCategory->getData($request));
    }

    /**
     * @throws AuthorizationException
     */
    public function getOfferingLettersProduct(OfferingLetter $offeringLetter): JsonResponse
    {
        $this->authorize('create', OfferingLetter::class);
        $this->authorize('update', OfferingLetter::class);
        $offeringLetterProduct = OfferingLetterProduct::where('offering_letter_id', $offeringLetter->id)->get();
        return response()->json($offeringLetterProduct);
    }

    /**
     * @throws AuthorizationException
     */
    public function getOfferingLetterDescription(OfferingLetter $offeringLetter): JsonResponse
    {
        $this->authorize('create', OfferingLetter::class);
        $this->authorize('update', OfferingLetter::class);
        $data = OfferingLetterServiceDescription::with('skl')->where('offering_letter_id', $offeringLetter->id)->get();
        return response()->json($data);
    }

    /**
     * @throws AuthorizationException
     */
    public function selectedUser(OfferingLetter $offeringLetter): JsonResponse
    {
        $this->authorize('create', OfferingLetter::class);
        $this->authorize('update', OfferingLetter::class);
        return response()->json($this->user->getSelectedData($offeringLetter->pic));
    }

    /**
     * @throws AuthorizationException
     */
    public function getUnitType(Request $request): JsonResponse
    {
        $this->authorize('create', OfferingLetter::class);
        $this->authorize('update', OfferingLetter::class);
        return response()->json($this->unitType->getData($request));
    }

    /**
     * @throws AuthorizationException
     */
    public function getSKL(Request $request): JsonResponse
    {
        $this->authorize('create', OfferingLetter::class);
        $this->authorize('update', OfferingLetter::class);
        return response()->json($this->offeringLetterSKL->getData($request));
    }

    /**
     * @throws AuthorizationException
     */
    public function getSelectedSKL(OfferingLetterServiceDescription $offeringLetterServiceDescription): JsonResponse
    {
        $this->authorize('update', OfferingLetter::class);
        return response()->json($this->offeringLetterSKL->getSelectedData($offeringLetterServiceDescription->skl_id));
    }

    /**
     * @throws \Throwable
     * @throws AuthorizationException
     */
    public function store(OfferingLetterRequest $request): JsonResponse
    {
        $this->authorize('create', OfferingLetter::class);
        $this->offeringLetterService->store($request);
        return response()->json([
            'message' => 'data berhasil disimpan',
        ]);
    }

    /**
     * @throws AuthorizationException
     */
    public function show(OfferingLetter $offeringLetter): View
    {
        $this->authorize('viewDetail', OfferingLetter::class);
        $offeringLetterProducts = OfferingLetterProduct::with('unitType')->where('offering_letter_id', $offeringLetter->id)->get();
        $offeringLetterServiceDescription = OfferingLetterServiceDescription::with('skl')
            ->where('offering_letter_id', $offeringLetter->id)
            ->get();

        $getPPN = TaxSetting::where('name', 'PPN')->first();


        $offeringLetterCompanyName = $this->convertCompanyNameToTextCapitalize($offeringLetter);

        $totalPPN = $getPPN->rate / 100 * $offeringLetterProducts->sum('price');
        $total = $offeringLetterProducts->sum('price') + $totalPPN;

        return view(
            'pages.transaction.offering-letter.detail',
            compact(
                'offeringLetter',
                'offeringLetterProducts',
                'totalPPN',
                'total',
                'offeringLetterServiceDescription',
                'offeringLetterCompanyName'
            )
        );
    }


    public function convertCompanyNameToTextCapitalize(OfferingLetter $offeringLetter): string
    {
        $companyName = strtolower($offeringLetter->contact->company_name);
        $convertCompanyNameToArray = explode(" ", $companyName);

        $newString = '';
        $newPTKey = '';

        if (($key = array_search('pt.' || 'pt', $convertCompanyNameToArray)) !== false) {
            $newPTKey = $convertCompanyNameToArray[$key];
            unset($convertCompanyNameToArray[$key]);
        }

        foreach ($convertCompanyNameToArray as $abbr) {
            $newString .= strtolower($abbr) . ' ';
        }

        return strtoupper($newPTKey) . ' ' . ucwords(trim($newString));
    }


    /**
     * @throws AuthorizationException
     */
    public function edit(OfferingLetter $offeringLetter): View
    {
        $this->authorize('update', $offeringLetter);
        return view('pages.transaction.offering-letter.edit', compact('offeringLetter'));
    }

    /**
     * @throws AuthorizationException
     */
    public function getSelectedContact(OfferingLetter $offeringLetter): JsonResponse
    {
        $this->authorize('update', $offeringLetter);
        $selected = $this->contact->getSelectedData($offeringLetter->contact_id);
        return response()->json($selected);
    }


    /**
     * @throws AuthorizationException
     */
    public function getSelectedUnitType(OfferingLetterProduct $offeringLetterProduct): JsonResponse
    {
        $this->authorize('update', OfferingLetter::class);
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

    /**
     * @throws \Throwable
     */
    public function update(OfferingLetterRequest $request, OfferingLetter $offeringLetter): JsonResponse
    {
        $this->authorize('update', $offeringLetter);
        $this->offeringLetterService->update($request, $offeringLetter);

        return response()->json([
            'message' => 'data berhasil disimpan',
        ]);
    }

    /**
     * @throws AuthorizationException
     */
    public function destroy(OfferingLetter $offeringLetter): JsonResponse
    {
        $this->authorize('delete', $offeringLetter);
        $offeringLetter->delete();

        return response()->json([
            'message' => 'data berhasil di hapus',
        ]);
    }

    /**
     * @throws AuthorizationException
     */
    public function exportToPDF(OfferingLetter $offeringLetter): Response
    {
        $this->authorize('print', $offeringLetter);
        $offeringLetterProducts = OfferingLetterProduct::with('unitType')->where('offering_letter_id', $offeringLetter->id)->get();

        $offeringLetterServiceDescription = OfferingLetterServiceDescription::with('skl')
            ->where('offering_letter_id', $offeringLetter->id)
            ->get();

        $getPPN = TaxSetting::where('name', 'PPN')->first();


        $offeringLetterCompanyName = $this->offeringLetterService->convertCompanyNameToCapitalLetter($offeringLetter);

        $totalPPN = $this->offeringLetterService->getPPNRate($offeringLetterProducts);
        $total = $offeringLetterProducts->sum('price') + $totalPPN;


        $view = view('pages.transaction.offering-letter.export-pdf', compact(
            'offeringLetter',
            'offeringLetterProducts',
            'offeringLetterServiceDescription',
            'total',
            'getPPN',
            'totalPPN',
            'offeringLetterCompanyName'));


        $pdf = Browsershot::html($view)
            ->setChromePath('/usr/bin/chromium')
            ->noSandbox()
            ->waitUntilNetworkIdle()
            ->ignoreHttpsErrors()
            ->format('A4')
            ->setEnvironmentOptions([
                'CHROME_CONFIG_HOME' => storage_path('app/chrome/.config')
            ])->pdf();


        return new Response($pdf, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="example.pdf',
        ]);
    }
}
