<?php

namespace App\Service\LeaveAndPermission;

use App\Models\LeaveAndPermission;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class UserLeaveAndPermissionService
{

    private LeaveAndPermission $leaveAndPermission;

    public function __construct()
    {
        $this->leaveAndPermission = new LeaveAndPermission();
    }

    public function data(Request $request, int $perPage = 10): LengthAwarePaginator
    {
        $query = $this->leaveAndPermission->getData()
            ->where('user_id', $request->user()->id)
            ->paginate($perPage);
        return self::formattedData($query);
    }


    private static function formattedData(LengthAwarePaginator $data): LengthAwarePaginator
    {
        $formattedData = $data->getCollection()->map(function ($item) {
            return [
                'id' => $item->id,
                'start_date' => Carbon::parse($item->start_date)->locale('id')->settings(
                    ['formatFunction' => 'translatedFormat']
                )->format('l, j F Y'),
                'end_date' => Carbon::parse($item->end_date)->locale('id')->settings(
                    ['formatFunction' => 'translatedFormat']
                )->format('l, j F Y'),
                'leaves_status' => $item->leaves_status,
                'confirmation_status' => $item->confirmation_status,
            ];
        });

        $data->setCollection($formattedData);

        return $data;
    }
}