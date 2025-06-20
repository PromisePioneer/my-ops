<?php

namespace App\Support\Master\Common\Contact\Service;

use AllowDynamicProperties;
use App\Enum\Contact\ContactType;
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
        $contacts = Contact::search($search)->query(function () {
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


    public function getContacts(Request $request)
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
}
