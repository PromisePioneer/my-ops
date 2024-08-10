<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use App\Http\Requests\Transaction\Fab\FabRequest;
use App\Models\Branch;
use App\Models\Contact;
use App\Models\Fab;
use App\Models\FabService;
use App\Models\ServiceCategory;
use App\Service\FabServices;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class FabController extends Controller
{
    public int $perPage = 10;

    private ServiceCategory $serviceCategory;

    private FabServices $fabServices;

    private Fab $fab;

    private Contact $contact;

    private Branch $branch;

    public function __construct()
    {
        $this->fabServices = new FabServices();
        $this->serviceCategory = new ServiceCategory();
        $this->fab = new Fab();
        $this->contact = new Contact();
        $this->branch = new Branch();
    }

    public function index()
    {
        return view('pages.transaction.fab.index');
    }

    public function data(Request $request): JsonResponse
    {
        return response()->json($this->fab->getDataBasedOnUserBranch($request, $this->perPage));
    }

    public function search(Request $request): JsonResponse
    {
        return response()->json($this->fab->searchDataBasedOnUserBranch($request));
    }

    public function branchData(Request $request): JsonResponse
    {
        return response()->json($this->branch->getData($request));
    }

    public function filterByBranch(Branch $branch): JsonResponse
    {
        return response()->json($this->fab->filterDataBasedOnBranch($branch->id, $this->perPage));
    }

    public function create()
    {
        return view('pages.transaction.fab.create');
    }

    public function contactData(Request $request): JsonResponse
    {
        $contact = $this->contact->getData($request);

        return response()->json($contact);
    }

    public function getServicesCategoriesData(Request $request): JsonResponse
    {
        $services = $this->serviceCategory->getData($request);

        return response()->json($services);
    }

    public function store(FabRequest $request): JsonResponse
    {
        $this->fabServices->store($request);

        return response()->json([
            'message' => 'Data berhasil disimpan',
        ]);
    }

    public function detail(Fab $fab): View
    {
        $fabServices = FabService::where('fab_id', $fab->id)->get();

        return view('pages.transaction.fab.detail', compact('fabServices', 'fab'));
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
        $fabServices = FabService::where('fab_id', $fab->id)->get();

        return response()->json($fabServices);
    }

    public function update(FabRequest $request, Fab $fab): JsonResponse
    {
        $this->fabServices->update($request, $fab);

        return response()->json([
            'message' => 'data berhasil disimpan',
        ]);
    }

    public function confirm(Fab $fab): JsonResponse
    {
        $fabService = FabService::where('fab_id', $fab->id)->get();
        $this->fabServices->confirm($fab, $fabService);

        return response()->json([
            'message' => 'data berhasil disimpan',
        ]);
    }

    public function jurnalEntry(Fab $fab): JsonResponse
    {
        $response = $this->fabServices->jurnalEntry($fab);

        return response()->json($response);
    }

    public function destroy(Fab $fab): JsonResponse
    {
        $fab->delete();

        return response()->json([
            'message' => 'data berhasil dihapus',
        ]);
    }

    public function exportPDF(Fab $fab): Response
    {
        $fabServices = FabService::where('fab_id', $fab->id)->get();

        $pdf = Pdf::loadView('pages.transaction.fab.export-pdf', compact('fab', 'fabServices'))->setPaper('A4', 'portrait');

        return $pdf->stream();
    }
}
