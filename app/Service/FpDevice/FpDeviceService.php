<?php

namespace App\Service\FpDevice;

use App\Models\FpDevice;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class FpDeviceService
{

    private static int $perPage = 10;

    public function data(Request $request): LengthAwarePaginator
    {
        $data = FpDevice::with(['branch', 'attendanceJobProgress' => function ($query) {
            $query->latest();
        }])->when($request->user()->hasRole('Branch Manager'), function ($query) use ($request) {
            $query->where('branch_id', $request->user()->branch_id);
        })
            ->paginate(self::$perPage);
        return self::formattedData($data);
    }


    public function search(Request $request): LengthAwarePaginator
    {
        $search = $request->input('search');
        $data = FpDevice::search($search)->query(function ($query) use ($request) {
            $fpDevice = $query->orderBy('name');
            FpDeviceQueryFilter::apply($fpDevice, $request);
        })->paginate(self::$perPage);

        return self::formattedData($data);
    }


    public function filter(Request $request): LengthAwarePaginator
    {
        $query = FpDevice::with('branch');
        $data = FpDeviceQueryFilter::apply($query, $request)
            ->paginate(self::$perPage);

        return self::formattedData($data);
    }


    private static function formattedData(LengthAwarePaginator $data): LengthAwarePaginator
    {
        $fpDevice = $data->getCollection()->map(function ($query) {
            return [
                'id' => $query->id,
                'name' => $query->name,
                'serial_number' => $query->serial_number,
                'ip_address' => $query->ip_address,
                'branch_name' => $query->branch?->name,
                'online' => $query->online ? 'Online' : 'Offline',
                'last_query_date' => $query->attendanceJobProgress?->created_at ? Carbon::parse($query->attendanceJobProgress?->created_at)->format('d m Y H:i:s') : '-',
                'status_query' => $query->attendanceJobProgress?->status ?? '-',
            ];
        });


        $data->setCollection($fpDevice);
        return $data;
    }
}
