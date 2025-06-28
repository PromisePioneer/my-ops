<?php

namespace App\Support\Master\Common\Contact\Service;

use AllowDynamicProperties;
use App\Enum\Contact\ContactType;
use App\Http\Requests\Master\Common\Contact\ContactRequest;
use App\Models\Master\Common\Contact;
use App\Support\Master\Common\Contact\Interface\ContactServiceInterface;
use App\Support\Master\Common\Contact\Repository\ContactRepository;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

#[AllowDynamicProperties] class ContactService implements ContactServiceInterface
{
    private static int $perPage = 10;

    public function __construct()
    {
        $this->contactRepository = new ContactRepository();
        $this->contact = new Contact();
    }


    public function data(): LengthAwarePaginator
    {
        $contacts = $this->contactRepository->data()->paginate(self::$perPage);
        return self::formattedData($contacts);
    }


    public function search(Request $request): LengthAwarePaginator
    {
        $search = $request->input('search');
        $contacts = $this->contact->search($search)->query(function () {
            $this->contactRepository->data();
        })->paginate(self::$perPage);

        return self::formattedData($contacts);
    }

    public static function formattedData(LengthAwarePaginator $data): LengthAwarePaginator
    {
        $contacts = $data->getCollection()->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => "$item->name - $item->code",
                'type' => $item->type === ContactType::SUPPLIER->value ? 'Supplier' : 'Klien',
            ];
        });

        $data->setCollection($contacts);
        return $data;
    }


    public function store(Request $request): Contact
    {
        return $this->contact->query()->create([
            'name' => $request->input('name'),
            'code' => $request->input('code'),
            'position' => $request->input('position'),
            'address' => $request->input('address'),
            'city' => $request->input('city'),
            'province' => $request->input('province'),
            'country' => $request->input('country'),
            'postal_code' => $request->input('postal_code'),
            'fax' => $request->input('fax'),
            'email' => $request->input('email'),
            'phone_number' => $request->input('phone_number'),
            'bank_account_number' => $request->input('bank_account_number'),
            'bank_account_name' => $request->input('bank_account_name'),
            'bank_name' => $request->input('bank_name'),
            'npwp' => $request->input('npwp'),
            'description' => $request->input('description'),
            'type' => $request->input('type'),
            'tax_type' => $request->input('tax_type'),
        ]);
    }


    public function update(ContactRequest $request, Contact $contact): bool
    {
        return $contact->update([
            'name' => $request->input('name'),
            'code' => $request->input('code'),
            'position' => $request->input('position'),
            'address' => $request->input('address'),
            'city' => $request->input('city'),
            'province' => $request->input('province'),
            'country' => $request->input('country'),
            'postal_code' => $request->input('postal_code'),
            'fax' => $request->input('fax'),
            'email' => $request->input('email'),
            'phone_number' => $request->input('phone_number'),
            'bank_account_number' => $request->input('bank_account_number'),
            'bank_account_name' => $request->input('bank_account_name'),
            'bank_name' => $request->input('bank_name'),
            'npwp' => $request->input('npwp'),
            'description' => $request->input('description'),
            'type' => $request->input('type'),
            'tax_type' => $request->input('tax_type'),
        ]);
    }


    public function getContacts(Request $request): Collection
    {
        $search = $request->input('search');
        $contacts = $this->contact->search($search)->query(
            fn() => $this->contactRepository->data()
        )->get();

        return $contacts->map(function ($contact) {
            return [
                'id' => $contact->id,
                'text' => "$contact->code - $contact->name"
            ];
        });
    }


    public function getSuppliers(Request $request): Collection
    {
        $search = $request->input('search');
        $suppliers = $this->contact->search($search)->query(
            fn() => $this->contactRepository->getSuppliers()
        )->get();

        return $suppliers->map(function ($supplier) {
            return [
                'id' => $supplier->id,
                'text' => "$supplier->code - $supplier->name ($supplier->tax_type)"
            ];
        });
    }


    public function getClients(Request $request): Collection
    {
        $search = $request->input('search');
        $clients = $this->contact->search($search)->query(
            fn() => $this->contactRepository->getClients()
        )->get();

        return $clients->map(function ($client) {
            return [
                'id' => $client->id,
                'text' => "$client->code - $client->name"
            ];
        });
    }

    public function selectedContact(Contact $contact): array
    {
        $contactTaxType = $contact->type === ContactType::SUPPLIER->value ? "($contact->tax_type)" : '';
        return [
            'id' => $contact->id,
            'name' => "$contact->code - $contact->name $contactTaxType"
        ];
    }


    public function trashedData(): LengthAwarePaginator
    {
        $data = $this->contact->onlyTrashed()->paginate(self::$perPage);
        return self::formattedData($data);
    }


    public function trashedSearch(Request $request): LengthAwarePaginator
    {
        $search = $request->input('search');
        $data = $this->contact::search($search)->onlyTrashed()->paginate(self::$perPage);
        return self::formattedData($data);
    }

    public function restore(Request $request, Contact $contact): ?bool
    {
        $implodeID = implode(',', $request->get('id'));
        $explodeID = explode(',', $implodeID);
        return $contact->whereIn('id', $explodeID)->restore();
    }


    /**
     * @throws \Exception
     */
    public function forceDelete(Request $request, Contact $contact): void
    {
        $contacts = $contact->with(['transaction', 'offeringLetter', 'initialInventoryBalance'])->whereIn('id', $request->get('id'))
            ->onlyTrashed()
            ->get();

        foreach ($contacts as $archivedContact) {
            if ($archivedContact->ifHasRelatedData($archivedContact)) {
                throw new \Exception('Data sudah terikat dengan data lainnya, tidak bisa dihapus secara permanen!');
            } else {
                $archivedContact->forceDelete();
            }
        }
    }


}
