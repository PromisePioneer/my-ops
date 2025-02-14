<?php

namespace App\Service;

use App\Models\PSB;
use Illuminate\Http\Request;
use function App\Helper\formatDate;

class PSBService
{
    private static int $perPage = 10;

    public function data()
    {
        $psb = PSB::with('area', 'area.areaHasUser', 'area.branch')->paginate(self::$perPage);
        return self::formattedData($psb);
    }


    public function search(Request $request)
    {
        $search = $request->search;
        $psb = PSB::with('broadbandPacket', 'branch', 'user')
            ->when(!empty($search), function ($query) use ($search) {
                $query->whereHas('user', function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%');
                });
            })->paginate(self::$perPage);

        return self::formattedData($psb);
    }


    private static function formattedData($psb)
    {
        $data = $psb->getCollection()->map(function ($item) {
            return [
                'date' => formatDate($item->date),
                'registration_date' => formatDate($item->registration_date),
                'active_date' => formatDate($item->active_date),
                'customer_name' => $item->customer_name,
                'phone_number' => $item->phone_number,
                'address' => $item->address,
                'area_id' => $item->area->name,
                'user_has_area_count' => $item->area->areaHasUser->count(),
                'vendor' => $item->area->areaHasUser->map(function ($item) {
                    return [
                        'name' => $item->user->name
                    ];
                }),
            ];
        });

        $psb->getCollection($data);
        return $psb;
    }
}
