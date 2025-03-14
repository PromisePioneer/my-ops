<?php

namespace App\Http\Controllers\Accounting\Transaction;

use AllowDynamicProperties;
use App\Http\Controllers\Controller;
use App\Http\Requests\Transaction\Fab\FabRequest;
use App\Models\CompanyProfile;
use App\Models\Fab;
use App\Models\FabHasSKL;
use App\Models\FabServiceCategory;
use App\Models\Master\Common\ServiceCategory;
use App\Models\TaxSetting;
use App\Support\IncomeTransaction\FabService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Spatie\Browsershot\Browsershot;
use Throwable;

#[AllowDynamicProperties] class FabController extends Controller
{
    public function __construct()
    {
        $this->fabService = new FabService();
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

        $companyName = strtolower($fab->po->contact->company_name);


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


    public function getFab(Request $request): JsonResponse
    {
        return response()->json($this->fabService->getFab($request));
    }

    public function selectedFab(Fab $fab): JsonResponse
    {
        return response()->json($this->fabService->selectedFab($fab));
    }
}
