<?php

namespace App\Service\Master\General;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class ContactService
{
    private static int $perPage = 10;

    public function data(): LengthAwarePaginator
    {
        $contacts = Contact::orderBy('company_name', 'asc')
            ->paginate(self::$perPage);
        return self::formattedData($contacts);
    }


    public function search(Request $request): LengthAwarePaginator
    {
        $search = $request->input('search');
        $contacts = Contact::search($search)->query(function ($query) {
            $query->orderBy('company_name');
        })->paginate(self::$perPage);

        return self::formattedData($contacts);
    }

    private static function formattedData(LengthAwarePaginator $contacts): LengthAwarePaginator
    {
        $data = $contacts->getCollection()->map(function ($item) {
            return [
                'id' => $item->id,
                'pic' => $item->pic_name,
                'company_name' => $item->company_name . '-' . $item->company_code,
                'phone_number' => $item->phone_number,
            ];
        });

        $contacts->setCollection($data);
        return $contacts;
    }
}
