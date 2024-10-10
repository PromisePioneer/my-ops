<?php

namespace App\Service;

use App\Models\ODP;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class ODPService
{

    private static int $perPage = 10;

    public function data(): LengthAwarePaginator
    {
        $data = ODP::with('area', 'area.branch')->paginate(self::$perPage);
        return self::formattedData($data);
    }


    public function search(Request $request): LengthAwarePaginator
    {
        $search = $request->input('search');
        $data = ODP::with('area', 'area.branch')->orderBy('cut_off_date');

        if ($search) {
            $data->whereHas('area', function ($query) use ($search) {
                $query->where('code', 'like', '%'.$search.'%');
            })->orWhereHas('area.branch', function ($query) use ($search) {
                $query->where('name', 'like', '%'.$search.'%');
            });
        }

        $odp = $data->paginate(self::$perPage);
        return self::formattedData($odp);
    }

    public function formattedData(LengthAwarePaginator $odp): LengthAwarePaginator
    {
        $data = $odp->getCollection()->map(function ($item) {
            return [
                'id' => $item->id,
                'branch' => $item->area->branch->name,
                'area' => $item->area->code,
                'name' => $item->name,
                'classification' => $item->classification,
                'passive_splitter' => $item->passive_splitter,
                'coordinates' => $item->lat.','.$item->long,
                'max_capacity' => $item->max_capacity,
                'used_capacity' => $item->used_capacity,
                'cut_off_date' => $item->cut_off_date,
            ];
        });

        $odp->setCollection($data);
        return $odp;
    }
}