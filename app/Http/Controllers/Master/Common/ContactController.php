<?php

namespace App\Http\Controllers\Master\Common;

use AllowDynamicProperties;
use App\Http\Controllers\Controller;
use App\Http\Requests\Master\Common\Contact\ContactRequest;
use App\Models\Master\Common\Branch;
use App\Models\Master\Common\Contact;
use App\Support\Master\Common\Contact\Service\ContactService;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Psy\Util\Json;

#[AllowDynamicProperties] class ContactController extends Controller
{

    public function __construct()
    {
        $this->contactService = new ContactService();
    }

    /**
     * @throws AuthorizationException
     */
    public function index(): View
    {
        $this->authorize('view', Contact::class);
        return view('pages.master.common.contacts.index');
    }


    /**
     * @throws AuthorizationException
     */
    public function data(): JsonResponse
    {
        $this->authorize('view', Contact::class);
        return response()->json($this->contactService->data());
    }

    /**
     * @throws AuthorizationException
     */
    public function search(Request $request): JsonResponse
    {
        $this->authorize('view', Contact::class);

        return response()->json($this->contactService->search($request));
    }

    /**
     * @throws AuthorizationException
     */
    public function store(ContactRequest $request): JsonResponse
    {
        $this->authorize('create', Contact::class);
        $this->contactService->store($request);
        return response()->json([
            'message' => 'data berhasil disimpan',
        ], 200);
    }

    /**
     * @throws AuthorizationException
     */
    public function edit(Contact $contact): JsonResponse
    {
        $this->authorize('update', Contact::class);
        return response()->json($contact);
    }

    /**
     * @throws AuthorizationException
     */
    public function update(ContactRequest $request, Contact $contact): JsonResponse
    {
        $this->authorize('update', Contact::class);
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
        $this->authorize('delete', Contact::class);
        $implodeID = implode(',', $request->get('id'));
        $explodeID = explode(',', $implodeID);
        $contact->whereIn('id', $explodeID)->delete();
        return response()->json(['message' => 'data berhasil dihapus']);
    }


    public function getContacts(Request $request): JsonResponse
    {
        return response()->json($this->contactService->getContacts($request));
    }

    public function getSuppliers(Request $request): JsonResponse
    {
        return response()->json($this->contactService->getSuppliers($request));
    }

    public function getClients(Request $request): JsonResponse
    {
        return response()->json($this->contactService->getClients($request));
    }

    public function selectedContact(Contact $contact): JsonResponse
    {
        return response()->json($this->contactService->selectedContact($contact));
    }


    public function trashed(): View
    {
        $this->authorize('viewArchives', Contact::class);
        return view('pages.master.common.contacts.archives');
    }

    public function trashedData(): JsonResponse
    {
        $this->authorize('viewArchives', Contact::class);
        return response()->json($this->contactService->trashedData());
    }


    public function trashedSearch(Request $request): JsonResponse
    {
        $this->authorize('viewArchives', Contact::class);
        return response()->json($this->contactService->trashedSearch($request));
    }


    public function restore(Request $request, Contact $contact): JsonResponse
    {
        $this->authorize('viewArchives', Contact::class);
        return response()->json($this->contactService->restore($request, $contact));
    }

    /**
     * @throws Exception
     */
    public function forceDelete(Request $request, Contact $contact): JsonResponse
    {
        $this->authorize('viewArchives', Contact::class);
        $this->contactService->forceDelete($request, $contact);
        return response()->json(['message' => 'data berhasil dihapus secara permanen']);
    }

}
