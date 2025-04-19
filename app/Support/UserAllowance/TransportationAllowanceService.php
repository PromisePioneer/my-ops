<?php

namespace App\Support\UserAllowance;

use App\Http\Requests\Allowances\TransportationAllowanceRequest;
use App\Models\UserHasTransportationAllowance;
use App\Support\HelperService\HandleFileUploadService;
use Illuminate\Pagination\LengthAwarePaginator;

use function App\Helper\formatDate;

class TransportationAllowanceService
{
    private UserHasTransportationAllowance $userHasTransportationAllowance;
    private HandleFileUploadService $handleFileUploadService;

    public function __construct()
    {
        $this->userHasTransportationAllowance = new UserHasTransportationAllowance();
        $this->handleFileUploadService = new HandleFileUploadService();
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
                'spk_image' => $item->spk_image,
            ];
        })->values();

        $transportationAllowance->setCollection($data);
        return $transportationAllowance;
    }


    public function store(TransportationAllowanceRequest $request): void
    {
        foreach ($request->user_id as $userId) {
            UserHasTransportationAllowance::create([
                'user_id' => $userId,
                'date' => $request->date,
                'transportation_type' => $request->transportation_type,
                'spk_image' => $this->handleFileUploadService->upload(
                    $request,
                    'documents/user/spk-image',
                    'spk_image',
                ),
                'amount' => $request->transportation_type === 'Dibawah 15 Km' ? 10000 : 12500,
            ]);
        }
    }


    public function update()
    {
    }

}
