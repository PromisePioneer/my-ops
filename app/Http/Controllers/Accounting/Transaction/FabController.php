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

    public function index(): View
    {
        return view('pages.transaction.fab.index');
    }

    public function data(): JsonResponse
    {
        return response()->json($this->fabService->data());
    }

    public function search(Request $request): JsonResponse
    {
        return response()->json($this->fabService->search($request));
    }


    public function branchData(Request $request): JsonResponse
    {
        return response()->json($this->branch->getData($request));
    }

    public function filterByBranch(Branch $branch): JsonResponse
    {
        return response()->json($this->fabService->filterByBranch($branch->id, $this->perPage));
    }

    public function create(): View
    {
        return view('pages.transaction.fab.create');
    }

    public function contactData(Request $request): JsonResponse
    {
        $contact = $this->contact->getData($request);
        return response()->json($contact);
    }

    public function getOfferingLetterIfExists(Contact $contact): JsonResponse
    {
        $test = OfferingLetter::where('contact_id', $contact->id)->first();
        return response()->json($test);
    }

    public function getServicesCategoriesData(Request $request): JsonResponse
    {
        $services = $this->serviceCategory->getData($request);
        return response()->json($services);
    }


    public function getSKL(Request $request): JsonResponse
    {
        return response()->json($this->skl->getData($request));
    }

    public function getUnitType(Request $request): JsonResponse
    {
        return response()->json($this->unitType->getData($request));
    }

    public function getUserData(Request $request): JsonResponse
    {
        return response()->json($this->user->getUser($request));
    }

    public function getSelectedUser(Fab $fab): JsonResponse
    {
        return response()->json($this->user->getSelectedData($fab->pic));
    }

    /**
     * @throws Throwable
     */
    public function store(FabRequest $request): JsonResponse
    {
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

    public function detail(Fab $fab): View
    {
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

    public function edit(Fab $fab): View
    {
        return view('pages.transaction.fab.edit', compact('fab'));
    }

    public function selectedContact(Fab $fab): JsonResponse
    {
        $selectedContact = $this->contact->getSelectedData($fab->contact_id);

        return response()->json($selectedContact);
    }

    public function selectedServices(Fab $fab): JsonResponse
    {
        $fabService = FabServiceCategory::where('fab_id', $fab->id)->get();
        return response()->json($fabService);
    }

    public function selectedSKL(Fab $fab): JsonResponse
    {
        $fabSKL = FabHasSKL::where('fab_id', $fab->id)->get();
        return response()->json($fabSKL);
    }

    /**
     * @throws Throwable
     */
    public function update(FabRequest $request, Fab $fab): JsonResponse
    {
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
        $this->fabService->confirm($fab);

        return response()->json([
            'message' => 'data berhasil disimpan',
        ]);
    }

    public function destroy(Fab $fab): JsonResponse
    {
        $fab->delete();

        return response()->json([
            'message' => 'data berhasil dihapus',
        ]);
    }

    public function exportPDF(Fab $fab)
    {
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
}
