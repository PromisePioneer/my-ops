<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use App\Http\Requests\Transaction\Bast\BastRequest;
use App\Models\Bast;
use App\Models\BastProduct;
use App\Models\Branch;
use App\Models\Contact;
use App\Service\BastServices;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class BastController extends Controller
{
    public int $perPage = 10;
    private Contact $contact;
    private BastServices $bastService;
    private Bast $bast;
    private BastProduct $bastProduct;
    private Branch $branch;

    public function __construct()
    {
        $this->contact = new Contact();
        $this->bastService = new BastServices();
        $this->bast = new Bast();
        $this->bastProduct = new BastProduct();
        $this->branch = new Branch();
    }

    public function index(): View
    {
        return view('pages.transaction.bast.index');
    }

    public function data(Request $request): JsonResponse
    {
        $bast = $this->bast->getDataWithPagination($request, $this->perPage);
        return response()->json($bast);
    }

    public function search(Request $request): JsonResponse
    {
        $searchQuery = $this->bast->searchDataBasedOnUserBranch($request);
        return response()->json($searchQuery);
    }

    public function branchData(Request $request): JsonResponse
    {
        return response()->json($this->branch->getData($request));
    }

    public function filterByBranch(Branch $branch): JsonResponse
    {
        return response()->json($this->bast->filterDataBasedOnBranch($branch->id, $this->perPage));
    }

    public function contactData(Request $request): JsonResponse
    {
        $contact = $this->contact->getData($request);
        return response()->json($contact);
    }

    public function create(): View
    {
        return view('pages.transaction.bast.create');
    }

    public function store(BastRequest $request): JsonResponse
    {
        $this->bastService->store($request);

        return response()->json([
            'message' => 'Data berhasil disimpan'
        ]);
    }

    public function edit(Bast $bast): View
    {
        return view('pages.transaction.bast.edit', compact('bast'));
    }

    public function update(BastRequest $request, Bast $bast): JsonResponse
    {
        $this->bastService->update($request, $bast);
        return response()->json([
            'message' => 'Data berhasil disimpan'
        ]);
    }

    public function detail(Bast $bast): View
    {
        $bastProducts = $this->bastProduct->getData($bast->id);
        return view('pages.transaction.bast.detail', compact('bast', 'bastProducts'));
    }


    public function getProductBast(Bast $bast): JsonResponse
    {
        return response()->json($this->bastProduct->getData($bast->id));
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


    public function destroy(Bast $bast): JsonResponse
    {
        Storage::delete($bast->file);
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
        $bastProducts = $this->bastProduct->getData($bast->id);

        $pdf = PDF::loadView('pages.transaction.bast.export-pdf', compact('bastProducts', 'bast'))->setPaper("A4", 'portrait');

        return $pdf->stream();
    }
}
