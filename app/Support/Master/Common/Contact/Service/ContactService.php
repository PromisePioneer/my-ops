<?php

namespace App\Support\Master\Common\Contact\Service;

use AllowDynamicProperties;
use App\Models\Master\Common\Contact;
use App\Support\Master\Common\Contact\Interface\ContactServiceInterface;
use App\Support\Master\Common\Contact\Repository\ContactRepository;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

#[AllowDynamicProperties] class ContactService implements ContactServiceInterface
{
    private static int $perPage = 10;

    public function __construct()
    {
        $this->contactRepository = new ContactRepository();
    }


    public function data(): LengthAwarePaginator
    {
        $contacts = $this->contactRepository->handle()->paginate(self::$perPage);
        return self::formattedData($contacts);
    }


    public function search(Request $request): LengthAwarePaginator
    {
        $search = $request->input('search');
        $contacts = Contact::search($search)->query(function () {
            $this->contactRepository->handle();
        })->paginate(self::$perPage);

        return self::formattedData($contacts);
    }

    public static function formattedData(LengthAwarePaginator $data): LengthAwarePaginator
    {
        $contacts = $data->getCollection()->map(function ($item) {
            return [
                'id' => $item->id,
                'pic' => $item->pic_name,
                'company_name' => $item->company_name . '-' . $item->company_code,
                'phone_number' => $item->phone_number,
            ];
        });

        $data->setCollection($contacts);
        return $data;
    }


    public function getContacts(Request $request)
    {
        $search = $request->input('search');
        $contacts = Contact::search($search)->query(fn() => $this->contactRepository->handle())->get();

        return $contacts->map(function ($contact) {
            return [
                'id' => $contact->id,
                'text' => ($contact->company_code . ' - ' . $contact->company_name)
            ];
        });
    }

    public function selectedContact(Contact $contact): array
    {
        return [
            'id' => $contact->id,
            'name' => ($contact->company_code . ' - ' . $contact->company_name)
        ];
    }
}
