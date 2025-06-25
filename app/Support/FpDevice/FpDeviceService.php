<?php

namespace App\Support\FpDevice;

use AllowDynamicProperties;
use App\Models\FpDevice;
use App\Support\FpDevice\Repository\FpDeviceRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

#[AllowDynamicProperties] class FpDeviceService
{

    private static int $perPage = 10;

    public function __construct()
    {
        $this->fpDeviceRepository = new FpDeviceRepository();
        $this->fpDevice = new FpDevice();
    }

    public function data(Request $request): LengthAwarePaginator
    {
        $data = $this->fpDeviceRepository->data($request)->paginate(self::$perPage);
        return self::formattedData($data);
    }


    public function search(Request $request): LengthAwarePaginator
    {
        $search = $request->input('search');
        $data = $this->fpDevice->search($search)->query(function (Builder $query) use ($request) {
            FpDeviceQueryFilter::apply($query, $request);
        })->paginate(self::$perPage);
        return self::formattedData($data);
    }


    public function filter(Request $request): LengthAwarePaginator
    {
        $query = $this->fpDevice->with('branch');
        $data = FpDeviceQueryFilter::apply($query, $request)->paginate(self::$perPage);
        return self::formattedData($data);
    }


    private static function formattedData(LengthAwarePaginator $data): LengthAwarePaginator
    {
        $fpDevice = $data->getCollection()->map(function ($query) {
            return [
                'id' => $query->id,
                'serial_number' => $query->serial_number,
                'ip_address' => $query->ip_address,
                'branch_name' => "{$query->branch->parent->name} - {$query->branch?->name}",
                'online' => $query->online ? 'Online' : 'Offline',
                'last_download_date' => $query->attendanceJobProgress?->created_at ? Carbon::parse($query->attendanceJobProgress->created_at) : null,
                'last_download_status' => $query->attendanceJobProgress?->status ?? null,
            ];
        });


        $data->setCollection($fpDevice);
        return $data;
    }
}
