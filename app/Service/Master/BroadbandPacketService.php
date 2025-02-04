<?php

namespace App\Service\Master;

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
    public function data(Request $request): LengthAwarePaginator
    {
        $data = BroadbandPacket::with('branch')->paginate(self::$perPage);

        return self::formattedData($data);
    }

    public function search(Request $request): LengthAwarePaginator
    {
        $branchId = $request->branch_id;
        $search = $request->input('search');
        $searchQuery = $this->broadbandPacket->with('branch')
            ->whereHas('branch', function ($query) use ($branchId) {
                $query->where('id', $branchId);
            })
            ->when(!empty($search), function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhereHas('branch', function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            })->orWhere('capacity', 'like', '%' . $search . '%');
        })->paginate(self::$perPage);

        return self::formattedData($searchQuery);
    }

    public function filter(Request $request): LengthAwarePaginator
    {
        $branchId = $request->branch_id;
        $filterQuery = $this->broadbandPacket->with('branch')
            ->whereHas('branch', function ($query) use ($branchId) {
                $query->where('id', $branchId);
            })->paginate(self::$perPage);

        return self::formattedData($filterQuery);
    }

    public function formattedData(LengthAwarePaginator $broadbandPacket): LengthAwarePaginator
    {
        $data = $broadbandPacket->getCollection()->map(function ($item) {
            return [
                'id' => $item->id,
                'branch_name' => $item->branch?->name,
                'name' => $item->name,
                'capacity' => $item->capacity,
                'price' => number_format($item->price, 2),
            ];
        });


        $broadbandPacket->setCollection($data);
        return $broadbandPacket;
    }
}
