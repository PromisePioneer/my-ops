<?php

namespace App\Support\SalesBonus;

use App\Http\Requests\Benefit\SalesBonusRequest;
use App\Models\BroadbandPacket;
use App\Models\SaleBonus;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

use function App\Helper\formatDate;

class SalesBonusService
{

    private static int $bonusPercentage = 20;

    public function data(): LengthAwarePaginator
    {
        $data = SaleBonus::with('user', 'packet')->paginate(10);
        return self::formattedData($data);
    }

    private static function formattedData(LengthAwarePaginator $saleBonusData): LengthAwarePaginator
    {
        $query = $saleBonusData->getCollection()->map(function ($item) {
            return [
                'id' => $item->id,
                'date_active' => formatDate($item->date_active),
                'customer_name' => $item->customer_name,
                'packet_name' => $item->packet->name,
                'packet_price' => number_format($item->packet->price),
                'sales' => $item->user->name,
                'amount' => number_format($item->amount),
            ];
        });


        $saleBonusData->setCollection($query);

        return $saleBonusData;
    }

    public function search(Request $request): LengthAwarePaginator
    {
        $search = $request->input('search');
        $query = SaleBonus::with('user', 'packet');


        if (!empty($search)) {
            $query->where('date_active', 'like', '%'.$search.'%')
                ->orWhere('customer_name', 'like', '%'.$search.'%')
                ->orWhereHas('user', function ($query) use ($search) {
                    $query->where('name', 'like', '%'.$search.'%');
                })->orWhereHas('packet', function ($query) use ($search) {
                    $query->where('name', 'like', '%'.$search.'%');
                });
        }


        $data = $query->paginate(10);
        return self::formattedData($data);
    }

    public function store(SalesBonusRequest $request)
    {
        $packet = BroadbandPacket::where('id', $request->packet_id)->first();
        $data = $request->validated();
        $data['amount'] = $packet->price / 100 * self::$bonusPercentage;
        return SaleBonus::create($data);
    }

    public function update(SalesBonusRequest $request, SaleBonus $saleBonus): bool
    {
        $packet = BroadbandPacket::where('id', $request->packet_id)->first();
        $data = $request->validated();
        $data['amount'] = $packet->price / 100 * self::$bonusPercentage;
        $saleBonus->update($data);

        return $saleBonus->update($data);
    }
}
