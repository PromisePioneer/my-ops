<?php

namespace App\Http\Controllers\Master\General;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\Contact\ContactRequest;
use App\Models\Branch;
use App\Models\Contact;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactController extends Controller
{
    public int $perPage = 10;

    private Contact $contact;

    private Branch $branch;

    public function __construct()
    {
        $this->contact = new Contact();
        $this->branch = new Branch();
    }

    /**
     * @throws AuthorizationException
     */
    public function index(): View
    {
        $this->authorize('view', Contact::class);
        return view('pages.general-master-data.contact.index');
    }

    /**
     * @throws AuthorizationException
     */
    public function data(Request $request): JsonResponse
    {
        $this->authorize('view', Contact::class);
        return response()->json(
            $this->contact->getDataWithPaginationBasedOnUserBranch(
                $request->user()->branch_id,
                $this->perPage
            )
        );
    }

    /**
     * @throws AuthorizationException
     */
    public function search(Request $request): JsonResponse
    {
        $this->authorize('view', Contact::class);
        return response()->json($this->contact->searchDataBasedOnUserBranch($request));
    }

    /**
     * @throws AuthorizationException
     */
    public function branchData(Request $request): JsonResponse
    {
        $this->authorize('view', Contact::class);
        return response()->json($this->branch->getData($request));
    }

    /**
     * @throws AuthorizationException
     */
    public function filterByBranch(Branch $branch): JsonResponse
    {
        $this->authorize('view', Contact::class);
        return response()->json($this->contact->filterDataBasedOnUserBranch($branch->id, $this->perPage));
    }

    /**
     * @throws AuthorizationException
     */
    public function store(ContactRequest $request): JsonResponse
    {
        $this->authorize('create', Contact::class);
        Contact::create($request->validated());

        return response()->json([
            'message' => 'data berhasil disimpan',
        ], 200);
    }

    /**
     * @throws AuthorizationException
     */
    public function edit(Contact $contact): JsonResponse
    {
        $this->authorize('update', $contact);
        return response()->json($contact);
    }

    /**
     * @throws AuthorizationException
     */
    public function update(ContactRequest $request, Contact $contact): JsonResponse
    {
        $this->authorize('update', $contact);
        $contact->update($request->validated());

        return response()->json([
            'message' => 'data berhasil disimpan',
        ], 200);
    }

    /**
     * @throws AuthorizationException
     */
    public function destroy(Request $request, Contact $contact): JsonResponse
    {
        $this->authorize('delete', $contact);
        $implodeID = implode(',', $request->get('id'));
        $explodeID = explode(',', $implodeID);
        $contact->whereIn('id', $explodeID)->delete();

        return response()->json([
            'message' => 'data berhasil dihapus',
        ], 200);
    }
}
