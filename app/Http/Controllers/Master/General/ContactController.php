<?php

namespace App\Http\Controllers\Master\General;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\Contact\ContactRequest;
use App\Models\Contact;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactController extends Controller
{
    private static int $perPage = 10;

    /**
     * @throws AuthorizationException
     */
    public function index(): View
    {
        $this->authorize('Lihat Kontak');
        return view('pages.general-master-data.contact.index');
    }


    /**
     * @throws AuthorizationException
     */
    public function data(Request $request): JsonResponse
    {
        $this->authorize('Lihat Kontak');
        $contact = Contact::paginate(self::$perPage);
        return response()->json($contact);
    }

    /**
     * @throws AuthorizationException
     */
    public function search(Request $request): JsonResponse
    {
        $this->authorize('Lihat Kontak');
        $search = $request->input('search');
        $contact = Contact::when(!empty($search), function ($query) use ($search) {
            $query->where('full_name', 'like', '%' . $search . '%')
                ->orWhere('company_name', 'like', '%' . $search . '%')
                ->orWhere('company_code', 'like', '%' . $search . '%')
                ->orWhere('email', 'like', '%' . $search . '%')
                ->orWhere('phone_number', 'like', '%' . $search . '%')
                ->orWhere('identity_type', 'like', '%' . $search . '%')
                ->orWhere('identity_number', 'like', '%' . $search . '%')
                ->orWhere('fax', 'like', '%' . $search . '%')
                ->orWhere('npwp', 'like', '%' . $search . '%')
                ->orWhere('complete_address', 'like', '%' . $search . '%')
                ->orWhere('other_info', 'like', '%' . $search . '%');
        })->paginate(self::$perPage);

        return response()->json($contact);
    }

    /**
     * @throws AuthorizationException
     */
    public function store(ContactRequest $request): JsonResponse
    {
        $this->authorize('Tambah Kontak');
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
        $this->authorize('Edit Kontak');
        return response()->json($contact);
    }

    /**
     * @throws AuthorizationException
     */
    public function update(ContactRequest $request, Contact $contact): JsonResponse
    {
        $this->authorize('Edit Kontak');
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
        $this->authorize('Hapus Kontak');
        $implodeID = implode(',', $request->get('id'));
        $explodeID = explode(',', $implodeID);
        $contact->whereIn('id', $explodeID)->delete();
        return response()->json(['message' => 'data berhasil dihapus']);
    }
}
