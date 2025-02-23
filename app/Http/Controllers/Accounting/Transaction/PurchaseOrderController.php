<?php

namespace App\Http\Controllers\Accounting\Transaction;

use AllowDynamicProperties;
use App\Http\Controllers\Controller;
use App\Http\Requests\PORequest;
use App\Models\Contact;
use App\Models\OfferingLetter;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\TaxSetting;
use App\Models\UnitType;
use App\Models\User;
use App\Service\PurchaseOrderService;
use Illuminate\Auth\Access\AuthorizationException;
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

    /**
     * @throws AuthorizationException
     */
    public function index(): View
    {
        $this->authorize('viewAny', PurchaseOrder::class);
        return view('pages.transaction.purchase-orders.index');
    }

    /**
     * @throws AuthorizationException
     */
    public function data(): JsonResponse
    {
        $this->authorize('view', PurchaseOrder::class);
        return response()->json($this->purchaseOrderService->data());
    }

    /**
     * @throws AuthorizationException
     */
    public function search(Request $request): JsonResponse
    {
        $this->authorize('view', PurchaseOrder::class);
        return response()->json($this->purchaseOrderService->search($request));
    }


    /**
     * @throws AuthorizationException
     */
    public function getContactData(Request $request): JsonResponse
    {
        $this->authorize('create', PurchaseOrder::class);
        return response()->json($this->contact->getData($request));
    }

    /**
     * @throws AuthorizationException
     */
    public function getOfferingLetterIfExists(Contact $contact): JsonResponse
    {
        $this->authorize('create', PurchaseOrder::class);
        $data = OfferingLetter::where('contact_id', $contact->id)->first();
        return response()->json($data);
    }

    /**
     * @throws AuthorizationException
     */
    public function getUnitTypeData(Request $request): JsonResponse
    {
        $this->authorize('create', PurchaseOrder::class);
        return response()->json($this->unitType->getData($request));
    }

    /**
     * @throws AuthorizationException
     */
    public function getUserData(Request $request): JsonResponse
    {
        $this->authorize('create', PurchaseOrder::class);
        return response()->json($this->user->getUser($request));
    }


    /**
     * @throws AuthorizationException
     */
    public function create(): View
    {
        $this->authorize('create', PurchaseOrder::class);
        return view('pages.transaction.purchase-orders.create');
    }


    /**
     * @throws Throwable
     */
    public function store(PORequest $request): JsonResponse
    {
        $this->authorize('create', PurchaseOrder::class);
        $this->purchaseOrderService->store($request);
        return response()->json(['message' => 'Purchase Order berhasil ditambahkan.']);
    }


    /**
     * @throws AuthorizationException
     */
    public function selectedPIC(PurchaseOrder $purchaseOrder): JsonResponse
    {
        $this->authorize('update', PurchaseOrder::class);
        return response()->json($this->user->getSelectedData($purchaseOrder->pic));
    }

    /**
     * @throws AuthorizationException
     */
    public function selectedContact(PurchaseOrder $purchaseOrder): JsonResponse
    {
        $this->authorize('update', PurchaseOrder::class);
        return response()->json($this->contact->getSelectedData($purchaseOrder->contact_id));
    }

    /**
     * @throws AuthorizationException
     */
    public function getPurchaseOrderItem(PurchaseOrder $purchaseOrder): JsonResponse
    {
        $this->authorize('update', PurchaseOrder::class);
        $poItem = PurchaseOrderItem::where('po_id', $purchaseOrder->id)->get();
        return response()->json($poItem);
    }

    /**
     * @throws AuthorizationException
     */
    public function edit(PurchaseOrder $purchaseOrder): View
    {
        $this->authorize('update', PurchaseOrder::class);
        return view('pages.transaction.purchase-orders.edit', compact('purchaseOrder'));
    }


    /**
     * @throws Throwable
     */
    public function update(PurchaseOrder $purchaseOrder, PORequest $request): JsonResponse
    {
        $this->authorize('update', PurchaseOrder::class);
        $this->purchaseOrderService->update($request, $purchaseOrder);
        return response()->json(['message' => 'Purchase Order berhasil disimpan.']);
    }


    /**
     * @throws AuthorizationException
     */
    public function detail(PurchaseOrder $purchaseOrder): View
    {
        $this->authorize('viewDetail', PurchaseOrder::class);
        $purchaseOrderItem = PurchaseOrderItem::where('po_id', $purchaseOrder->id)->get();
        $poCompany = $this->purchaseOrderService->convertCompanyNameToTextCapitalize($purchaseOrder);

        $getPPN = TaxSetting::where('name', 'PPN')->first();
        $totalPPN = $getPPN->rate / 100 * $purchaseOrderItem->sum('price');
        $total = $purchaseOrderItem->sum('price') + $totalPPN;


        return view('pages.transaction.purchase-orders.detail', compact('purchaseOrder', 'purchaseOrderItem', 'poCompany', 'total', 'totalPPN'));
    }


    /**
     * @throws AuthorizationException
     */
    public function confirm(PurchaseOrder $purchaseOrder): JsonResponse
    {
        $this->authorize('confirm', PurchaseOrder::class);
        $purchaseOrder->update([
            'status' => 1
        ]);
        return response()->json();
    }


    /**
     * @throws AuthorizationException
     */
    public function destroy(PurchaseOrder $purchaseOrder): JsonResponse
    {
        $this->authorize('delete', PurchaseOrder::class);
        return response()->json($purchaseOrder->delete());
    }


    /**
     * @throws AuthorizationException
     */
    public function exportToPDF(PurchaseOrder $purchaseOrder): Response
    {
        $this->authorize('print', PurchaseOrder::class);
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
