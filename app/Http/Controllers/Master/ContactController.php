<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\Contact\ContactRequest;
use App\Models\Branch;
use App\Models\Contact;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactController extends Controller
{
    public int $perPage = 10;

    private Contact $contact;

    public function __construct()
    {
        $this->middleware('permission:lihat contact', ['only' => ['index']]);
        $this->middleware('permission:tambah contact', ['only' => ['create', 'store']]);
        $this->middleware('permission:update contact', ['only' => ['edit', 'update']]);
        $this->middleware('permission:hapus contact', ['only' => ['destroy']]);
        $this->contact = new Contact;
        $this->branch = new Branch;
    }

    public function index(): View
    {
        return view('pages.master.contact.index');
    }

    public function data(Request $request): JsonResponse
    {
        return response()->json($this->contact->getDataWithPaginationBasedOnUserBranch($request->user()->branch_id, $this->perPage));
    }

    public function search(Request $request): JsonResponse
    {
        return response()->json($this->contact->searchDataBasedOnUserBranch($request));
    }

    public function branchData(Request $request): JsonResponse
    {
        return response()->json($this->branch->getData($request));
    }

    public function filterByBranch(Branch $branch): JsonResponse
    {
        return response()->json($this->contact->filterDataBasedOnUserBranch($branch->id, $this->perPage));
    }

    public function create(): View
    {
        return view('pages.master.contact.create');
    }

    public function store(ContactRequest $request): JsonResponse
    {
        Contact::create($request->validated());

        return response()->json([
            'message' => 'data berhasil disimpan',
        ], 200);
    }

    public function edit(Contact $contact): JsonResponse
    {
        return response()->json($contact);
    }

    public function update(ContactRequest $request, Contact $contact): JsonResponse
    {
        $contact->update($request->validated());

        return response()->json([
            'message' => 'data berhasil disimpan',
        ], 200);
    }

    public function destroy(Request $request, Contact $contact): JsonResponse
    {
        $implodeID = implode(',', $request->get('id'));
        $explodeID = explode(',', $implodeID);
        $contact->whereIn('id', $explodeID)->delete();

        return response()->json([
            'message' => 'data berhasil dihapus',
        ], 200);
    }
}
