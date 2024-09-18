<?php

namespace App\Service\Master;

use App\Models\BroadbandPacket;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class BroadbandPacketService
{
    private static int $perPage = 10;
    private BroadbandPacket $broadbandPacket;

    public function __construct()
    {
        $this->broadbandPacket = new BroadbandPacket();
    }

    public function data(Request $request): LengthAwarePaginator
    {
        $data = $this->broadbandPacket->with('branch')
            ->where('branch_id', $request->user()->branch_id)
            ->paginate(self::$perPage);

        return self::formattedData($data);
    }

    public function formattedData(LengthAwarePaginator $broadbandPacket): LengthAwarePaginator
    {
        $data = $broadbandPacket->getCollection()->map(function ($item) {
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