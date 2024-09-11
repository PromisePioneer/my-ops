<?php

namespace App\Service;

use App\Models\UserHasTransportationAllowance;
use Illuminate\Pagination\LengthAwarePaginator;

use function App\Helper\formatDate;

class TransportationAllowanceService
{

    private UserHasTransportationAllowance $userHasTransportationAllowance;

    public function __construct()
    {
        $this->userHasTransportationAllowance = new UserHasTransportationAllowance();
    }

    public function data(): LengthAwarePaginator
    {
        $data = $this->userHasTransportationAllowance->data()->paginate(10);
        return self::formattedData($data);
    }

    private static function formattedData(LengthAwarePaginator $transportationAllowance): LengthAwarePaginator
    {
        $data = $transportationAllowance->getCollection()->map(function ($item) {
            return [
                'id' => $item->id,
                'date' => formatDate($item->date),
                'user_name' => $item->user->nip.' '.$item->user->name,
                'amount' => number_format($item->amount, 2, ',', '.'),
            ];
        })->values();

        $transportationAllowance->setCollection($data);
        return $transportationAllowance;
    }

}