<?php

namespace App\Http\Controllers\Accounting\Transaction;

use AllowDynamicProperties;
use App\Http\Controllers\Controller;
use App\Http\Requests\Transaction\Bast\BastRequest;
use App\Models\BAA;
use App\Models\Bast;
use App\Models\BastProduct;
use App\Models\Branch;
use App\Models\CompanyProfile;
use App\Models\Contact;
use App\Models\PurchaseOrderItem;
use App\Models\TaxSetting;
use App\Service\Transaction\BastService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Spatie\Browsershot\Browsershot;

#[AllowDynamicProperties] class BastController extends Controller
{
    public function __construct()
    {
        $this->contact = new Contact();
        $this->bastService = new BastService();
        $this->bast = new Bast();
        $this->bastProduct = new BastProduct();
        $this->branch = new Branch();
        $this->baa = new BAA();
    }

    public function index(): View
    {
        return view('pages.transaction.bast.index');
    }

    public function data(): JsonResponse
    {
        $bast = $this->bastService->data();
        return response()->json($bast);
    }

    public function search(Request $request): JsonResponse
    {
        $searchQuery = $this->bastService->search($request);
        return response()->json($searchQuery);
    }

    public function getBAAData(Request $request): JsonResponse
    {
        return response()->json($this->baa->getData($request));
    }

    public function branchData(Request $request): JsonResponse
    {
        return response()->json($this->branch->getData($request));
    }

    public function create(): View
    {
        return view('pages.transaction.bast.create');
    }

    public function store(BastRequest $request): JsonResponse
    {
        $this->bastService->store($request);

        return response()->json([
            'message' => 'Data berhasil disimpan',
        ]);
    }


    public function selectedBAA(Bast $bast): JsonResponse
    {
        return response()->json($this->baa->getSelectedData($bast->baa_id));
    }

    public function edit(Bast $bast): View
    {
        return view('pages.transaction.bast.edit', compact('bast'));
    }

    public function detail(Bast $bast): View
    {
        $getPoItem = PurchaseOrderItem::where('po_id', $bast->baa->fab->po->id)->get();
        $getPPN = TaxSetting::where('name', 'PPN')->first();
        $totalPPN = $getPPN->rate / 100 * $getPoItem->sum('price');
        $total = $getPoItem->sum('price') + $totalPPN;
        $companyProfile = CompanyProfile::first();
        return view('pages.transaction.bast.detail', compact('bast', 'getPoItem', 'totalPPN', 'total', 'companyProfile'));
    }


    public function getSelectedContact(Bast $bast): JsonResponse
    {
        return response()->json($this->contact->getSelectedData($bast->id));
    }

    public function confirm(Bast $bast): JsonResponse
    {
        $bast->update([
            'status' => 1,
        ]);

        return response()->json([
            'message' => 'data berhasil dikonfirmasi',
        ], 201);
    }

    public function update(BastRequest $request, Bast $bast): JsonResponse
    {
        $this->bastService->update($request, $bast);

        return response()->json([
            'message' => 'Data berhasil disimpan',
        ]);
    }

    public function destroy(Bast $bast): JsonResponse
    {
        $bast->delete();
        return response()->json([
            'message' => 'data berhasil dihapus',
        ], 201);
    }

    public function viewFile(Bast $bast)
    {
        return view('pages.transaction.bast.view-file', compact('bast'));
    }

    public function exportToPDF(Bast $bast): Response
    {

        $getPoItem = PurchaseOrderItem::where('po_id', $bast->baa->fab->po->id)->get();
        $getPPN = TaxSetting::where('name', 'PPN')->first();
        $totalPPN = $getPPN->rate / 100 * $getPoItem->sum('price');
        $total = $getPoItem->sum('price') + $totalPPN;
        $companyProfile = CompanyProfile::first();
        $view = view('pages.transaction.bast.export-pdf', compact('bast', 'getPoItem', 'totalPPN', 'total', 'companyProfile'))->render();
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
            'Content-Disposition' => 'inline; filename="' . $bast->bast_number . '".pdf"',
        ]);
    }
}
