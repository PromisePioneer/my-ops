<?php

namespace App\Support\Master\Common\BroadbandPacket;

use AllowDynamicProperties;
use App\Models\BroadbandPacket;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

#[AllowDynamicProperties] class BroadbandPacketService
{
    private static int $perPage = 10;


    public function __construct()
    {
        $this->broadbandPacket = new BroadbandPacket();
    }

    public function data(): LengthAwarePaginator
    {
        $data = $this->broadbandPacket->orderBy('price')->paginate(self::$perPage);
        return self::formattedData($data);
    }

    public function search(Request $request): LengthAwarePaginator
    {
        $search = $request->input('search');
        $searchQuery = BroadbandPacket::search($search)->query(callback: static function ($query) {
            $query->orderBy('name');
        })->paginate(self::$perPage);

        return self::formattedData($searchQuery);
    }

    public function formattedData(LengthAwarePaginator $broadbandPacket): LengthAwarePaginator
    {
        $data = $broadbandPacket->getCollection()->map(callback: function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'capacity' => $item->capacity,
                'price' => number_format($item->price, 2),
            ];
        });


        $broadbandPacket->setCollection($data);
        return $broadbandPacket;
    }
}
