<?php

namespace App\Support\Master\Common\BroadbandPacket;

use AllowDynamicProperties;
use App\Models\InternetPackage;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

#[AllowDynamicProperties] class InternetPackageService
{
    private static int $perPage = 10;


    public function __construct()
    {
        $this->internetPackage = new InternetPackage();
    }

    public function data(Request $request): LengthAwarePaginator
    {
        $data = $this->internetPackage
            ->query()
            ->where('company_id', $request->session()->get('company_session'))
            ->orderBy('price')
            ->paginate(self::$perPage);
        return self::formattedData($data);
    }

    public function search(Request $request): LengthAwarePaginator
    {
        $search = $request->input('search');
        $searchQuery = InternetPackage::search($search)->query(callback: static function ($query) use ($request) {
            $query->where('company_id', $request->session()->get('company_session'))
                ->orderBy('name');
        })->paginate(self::$perPage);

        return self::formattedData($searchQuery);
    }

    public function formattedData(LengthAwarePaginator $internetPackage): LengthAwarePaginator
    {
        $data = $internetPackage->getCollection()->map(callback: function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'capacity' => $item->capacity,
                'price' => number_format($item->price, 2),
            ];
        });


        $internetPackage->setCollection($data);
        return $internetPackage;
    }
}
