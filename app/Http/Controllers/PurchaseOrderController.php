<?php

namespace App\Http\Controllers;

use AllowDynamicProperties;
use App\Http\Requests\PORequest;
use App\Models\Contact;
use App\Models\OfferingLetter;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\UnitType;
use App\Models\User;
use App\Service\PurchaseOrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
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
        PurchaseOrderItem::where('po_id', $purchaseOrder->id)->get();
        return view('pages.transaction.purchase-orders.detail', compact('purchaseOrder'));
    }

    public function destroy()
    {

    }
}
