<?php

namespace App\Http\Controllers;

use AllowDynamicProperties;
use App\Http\Requests\PORequest;
use App\Models\Contact;
use App\Models\OfferingLetter;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\TaxSetting;
use App\Models\UnitType;
use App\Models\User;
use App\Service\PurchaseOrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Spatie\Browsershot\Browsershot;
use Throwable;

#[AllowDynamicProperties] class PurchaseOrderController extends Controller
{
    public function __construct()
    {
        $this->purchaseOrderService = new PurchaseOrderService();
        $this->contact = new Contact();
        $this->unitType = new UnitType();
        $this->user = new User();
    }

    public function index(): View
    {
        return view('pages.transaction.purchase-orders.index');
    }

    public function data(): JsonResponse
    {
        return response()->json($this->purchaseOrderService->data());
    }

    public function search(Request $request): JsonResponse
    {
        return response()->json($this->purchaseOrderService->search($request));
    }


    public function getContactData(Request $request): JsonResponse
    {
        return response()->json($this->contact->getData($request));
    }

    public function getOfferingLetterIfExists(Contact $contact): JsonResponse
    {
        $data = OfferingLetter::where('contact_id', $contact->id)->first();
        return response()->json($data);
    }

    public function getUnitTypeData(Request $request): JsonResponse
    {
        return response()->json($this->unitType->getData($request));
    }

    public function getUserData(Request $request): JsonResponse
    {
        return response()->json($this->user->getUser($request));
    }


    public function create(): View
    {
        return view('pages.transaction.purchase-orders.create');
    }


    /**
     * @throws Throwable
     */
    public function store(PORequest $request): JsonResponse
    {
        $this->purchaseOrderService->store($request);
        return response()->json(['message' => 'Purchase Order berhasil ditambahkan.']);
    }


    public function selectedPIC(PurchaseOrder $purchaseOrder): JsonResponse
    {
        return response()->json($this->user->getSelectedData($purchaseOrder->pic));
    }

    public function selectedContact(PurchaseOrder $purchaseOrder): JsonResponse
    {
        return response()->json($this->contact->getSelectedData($purchaseOrder->contact_id));
    }

    public function getPurchaseOrderItem(PurchaseOrder $purchaseOrder): JsonResponse
    {
        $poItem = PurchaseOrderItem::where('po_id', $purchaseOrder->id)->get();
        return response()->json($poItem);
    }

    public function edit(PurchaseOrder $purchaseOrder): View
    {
        return view('pages.transaction.purchase-orders.edit', compact('purchaseOrder'));
    }


    /**
     * @throws Throwable
     */
    public function update(PurchaseOrder $purchaseOrder, PORequest $request): JsonResponse
    {
        $this->purchaseOrderService->update($request, $purchaseOrder);
        return response()->json(['message' => 'Purchase Order berhasil disimpan.']);
    }


    public function detail(PurchaseOrder $purchaseOrder): View
    {
        $purchaseOrderItem = PurchaseOrderItem::where('po_id', $purchaseOrder->id)->get();
        $poCompany = $this->purchaseOrderService->convertCompanyNameToTextCapitalize($purchaseOrder);

        $getPPN = TaxSetting::where('name', 'PPN')->first();
        $totalPPN = $getPPN->rate / 100 * $purchaseOrderItem->sum('price');
        $total = $purchaseOrderItem->sum('price') + $totalPPN;


        return view('pages.transaction.purchase-orders.detail', compact('purchaseOrder', 'purchaseOrderItem', 'poCompany', 'total', 'totalPPN'));
    }


    public function confirm(PurchaseOrder $purchaseOrder): JsonResponse
    {
        $purchaseOrder->update([
            'status' => 1
        ]);
        return response()->json();
    }


    public function destroy(PurchaseOrder $purchaseOrder): JsonResponse
    {
        return response()->json($purchaseOrder->delete());
    }


    public function exportToPDF(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrderItem = PurchaseOrderItem::with('unitType')
            ->where('po_id', $purchaseOrder->id)
            ->get();

        $getPPN = TaxSetting::where('name', 'PPN')->first();


        $poCompany = $this->purchaseOrderService->convertCompanyNameToTextCapitalize($purchaseOrder);

        $totalPPN = $getPPN->rate / 100 * $purchaseOrderItem->sum('price');
        $total = $purchaseOrderItem->sum('price') + $totalPPN;


        $view = view('pages.transaction.purchase-orders.export-pdf', compact(
            'purchaseOrder', 'purchaseOrderItem', 'poCompany', 'total', 'totalPPN'));


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
