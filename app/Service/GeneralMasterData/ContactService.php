<?php

namespace App\Service\GeneralMasterData;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class ContactService
{
    private static int $perPage = 10;

    public function data()
    {
        $contacts = Contact::paginate(self::$perPage);
        return self::formattedData($contacts);
    }


    public function search(Request $request): LengthAwarePaginator
    {
        $search = $request->input('search');
        $contacts = Contact::when(!empty($search), function ($query) use ($search) {
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
