<?php

namespace App\Http\Controllers\Accounting\Transaction;

use App\Http\Controllers\Controller;
use App\Http\Requests\Transaction\Fab\FabRequest;
use App\Models\Branch;
use App\Models\CompanyProfile;
use App\Models\Contact;
use App\Models\Fab;
use App\Models\FabHasSKL;
use App\Models\FabServiceCategory;
use App\Models\OfferingLetter;
use App\Models\ServiceCategory;
use App\Models\SKL;
use App\Models\TaxSetting;
use App\Models\UnitType;
use App\Models\User;
use App\Service\Transaction\FabService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Spatie\Browsershot\Browsershot;
use Throwable;

class FabController extends Controller
{
    public int $perPage = 10;
    private ServiceCategory $serviceCategory;
    private Contact $contact;
    private Branch $branch;
    private FabService $fabService;
    private FabServiceCategory $fabServiceCategory;
    private SKL $skl;
    private UnitType $unitType;
    private User $user;

    public function __construct()
    {
        $this->serviceCategory = new ServiceCategory();
        $this->contact = new Contact();
        $this->branch = new Branch();
        $this->fabService = new FabService();
        $this->skl = new SKL();
        $this->unitType = new UnitType();
        $this->user = new User();
    }

    /**
     * @throws AuthorizationException
     */
    public function index(): View
    {
        $this->authorize('viewAny', Fab::class);
        return view('pages.transaction.fab.index');
    }

    /**
     * @throws AuthorizationException
     */
    public function data(): JsonResponse
    {
        $this->authorize('view', Fab::class);
        return response()->json($this->fabService->data());
    }

    /**
     * @throws AuthorizationException
     */
    public function search(Request $request): JsonResponse
    {
        $this->authorize('view', Fab::class);
        return response()->json($this->fabService->search($request));
    }

    /**
     * @throws AuthorizationException
     */
    public function create(): View
    {
        $this->authorize('create', Fab::class);
        return view('pages.transaction.fab.create');
    }

    /**
     * @throws AuthorizationException
     */
    public function contactData(Request $request): JsonResponse
    {
        $this->authorize('create', Fab::class);
        $this->authorize('update', Fab::class);
        $contact = $this->contact->getData($request);
        return response()->json($contact);
    }

    public function getOfferingLetterIfExists(Contact $contact): JsonResponse
    {
        $test = OfferingLetter::where('contact_id', $contact->id)->first();
        return response()->json($test);
    }

    /**
     * @throws AuthorizationException
     */
    public function getServicesCategoriesData(Request $request): JsonResponse
    {
        $this->authorize('create', Fab::class);
        $this->authorize('update', Fab::class);
        $services = $this->serviceCategory->getData($request);
        return response()->json($services);
    }


    /**
     * @throws AuthorizationException
     */
    public function getSKL(Request $request): JsonResponse
    {
        $this->authorize('create', Fab::class);
        $this->authorize('update', Fab::class);
        return response()->json($this->skl->getData($request));
    }

    /**
     * @throws AuthorizationException
     */
    public function getUnitType(Request $request): JsonResponse
    {
        $this->authorize('create', Fab::class);
        $this->authorize('update', Fab::class);
        return response()->json($this->unitType->getData($request));
    }

    /**
     * @throws AuthorizationException
     */
    public function getUserData(Request $request): JsonResponse
    {
        $this->authorize('create', Fab::class);
        $this->authorize('update', Fab::class);
        return response()->json($this->user->getUser($request));
    }

    /**
     * @throws AuthorizationException
     */
    public function getSelectedUser(Fab $fab): JsonResponse
    {
        $this->authorize('update', $fab);
        return response()->json($this->user->getSelectedData($fab->pic));
    }

    /**
     * @throws Throwable
     */
    public function store(FabRequest $request): JsonResponse
    {
        $this->authorize('create', Fab::class);
        $this->fabService->store($request);
        return response()->json([
            'message' => 'Data berhasil disimpan',
        ]);
    }


    public function convertCompanyNameToTextCapitalize(Fab $fab): string
    {

        $companyName = strtolower($fab->contact->company_name);


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
    public function detail(Fab $fab): View
    {

        $this->authorize('viewDetail', Fab::class);

        $fabHasServiceCategories = FabServiceCategory::with('service', 'unitType')
            ->where('fab_id', $fab->id)
            ->get();
        $serviceCategories = ServiceCategory::orderBy('name')->get();

        $fabHasServiceCategoriesCollection = FabServiceCategory::where('fab_id', $fab->id)
            ->pluck('service_category_id')
            ->toArray();

        $fabHasSKL = FabHasSKL::with('skl')->where('fab_id', $fab->id)->get();

        $test = [];
        foreach ($fabHasServiceCategoriesCollection as $s) {
            $test[] = $s;
        }

        $getPPN = TaxSetting::where('name', 'PPN')->first();

        $totalPPN = $getPPN->rate / 100 * $fabHasServiceCategories->sum('price');
        $total = $fabHasServiceCategories->sum('price') + $totalPPN;

        $fabCompanyName = $this->convertCompanyNameToTextCapitalize($fab);


        $companyProfile = CompanyProfile::where('id', 1)->first();

        return view('pages.transaction.fab.detail', compact('fabCompanyName', 'companyProfile', 'fabHasServiceCategories', 'fab', 'serviceCategories', 'test', 'total', 'totalPPN', 'fabHasSKL'));
    }

    public function viewFile(Fab $fab): View
    {
        return view('pages.transaction.fab.view-file', compact('fab'));
    }

    /**
     * @throws AuthorizationException
     */
    public function edit(Fab $fab): View
    {
        $this->authorize('update', $fab);

        return view('pages.transaction.fab.edit', compact('fab'));
    }

    /**
     * @throws AuthorizationException
     */
    public function selectedContact(Fab $fab): JsonResponse
    {
        $this->authorize('update', $fab);

        $selectedContact = $this->contact->getSelectedData($fab->contact_id);

        return response()->json($selectedContact);
    }

    /**
     * @throws AuthorizationException
     */
    public function selectedServices(Fab $fab): JsonResponse
    {
        $this->authorize('update', $fab);

        $fabService = FabServiceCategory::where('fab_id', $fab->id)->get();
        return response()->json($fabService);
    }

    /**
     * @throws AuthorizationException
     */
    public function selectedSKL(Fab $fab): JsonResponse
    {
        $this->authorize('update', $fab);

        $fabSKL = FabHasSKL::where('fab_id', $fab->id)->get();
        return response()->json($fabSKL);
    }

    /**
     * @throws Throwable
     */
    public function update(FabRequest $request, Fab $fab): JsonResponse
    {
        $this->authorize('update', $fab);

        $this->fabService->update($request, $fab);

        return response()->json([
            'message' => 'data berhasil disimpan',
        ]);
    }

    /**
     * @throws Throwable
     */
    public function confirm(Fab $fab): JsonResponse
    {
        $this->authorize('confirm', $fab);
        $this->fabService->confirm($fab);

        return response()->json([
            'message' => 'data berhasil disimpan',
        ]);
    }

    /**
     * @throws AuthorizationException
     */
    public function destroy(Fab $fab): JsonResponse
    {
        $this->authorize('delete', $fab);
        $fab->delete();
        return response()->json([
            'message' => 'data berhasil dihapus',
        ]);
    }

    /**
     * @throws AuthorizationException
     */
    public function exportPDF(Fab $fab): Response
    {
        $this->authorize('print', $fab);
        $fabHasServiceCategories = FabServiceCategory::with('service', 'unitType')
            ->where('fab_id', $fab->id)
            ->get();
        $serviceCategories = ServiceCategory::orderBy('name')->get();

        $fabHasServiceCategoriesCollection = FabServiceCategory::where('fab_id', $fab->id)
            ->pluck('service_category_id')
            ->toArray();

        $fabHasSKL = FabHasSKL::with('skl')->where('fab_id', $fab->id)->get();

        $test = [];
        foreach ($fabHasServiceCategoriesCollection as $s) {
            $test[] = $s;
        }

        $getPPN = TaxSetting::where('name', 'PPN')->first();

        $totalPPN = $getPPN->rate / 100 * $fabHasServiceCategories->sum('price');
        $total = $fabHasServiceCategories->sum('price') + $totalPPN;

        $fabCompanyName = $this->convertCompanyNameToTextCapitalize($fab);


        $companyProfile = CompanyProfile::where('id', 1)->first();


        $view = view('pages.transaction.fab.export-pdf',
            compact('fabCompanyName', 'companyProfile', 'fabHasServiceCategories', 'fab', 'serviceCategories', 'test', 'total', 'totalPPN', 'fabHasSKL'));


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
            'Content-Disposition' => 'inline; filename="example.pdf"',
        ]);
    }


    /**
     * @throws AuthorizationException
     */
    public function contractPDF(Fab $fab): Response
    {
        $this->authorize('printContract', $fab);


        $fabServiceCategories = FabServiceCategory::with('service')->where('fab_id', $fab->id)->get();

        $serviceCategories = [];

        foreach ($fabServiceCategories as $serviceCategory) {
            $serviceCategories[] = $serviceCategory->service->name;
        }

        $serviceCategories = implode(', ', $serviceCategories);


        $view = view('pages.transaction.fab.contract.index', compact('fab', 'serviceCategories', 'fabServiceCategories'));
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
            'Content-Disposition' => 'inline; filename="example.pdf"',
        ]);
    }
}
