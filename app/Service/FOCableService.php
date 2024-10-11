<?php

namespace App\Service;

use App\Models\FOCable;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class FOCableService
{
    private static int $perPage = 10;

    public function data(): LengthAwarePaginator
    {
        $data = FOCable::with('branch')->paginate(self::$perPage);

        return self::formattedData($data);
    }


    public function search(Request $request): LengthAwarePaginator
    {
        $search = $request->input('search');
        $data = FOCable::with('branch');


        if (!empty($search)) {
            $data->whereHas('branch', function ($query) use ($search) {
                $query->where('name', 'like', '%'.$search.'%');
            })->orWhere('segment_id', 'like', '%'.$search.'%')
                ->orWhere('cable_placement', 'like', '%'.$search.'%')
                ->orWhere('total_core', 'like', '%'.$search.'%')
                ->orWhere('cable_address', 'like', '%'.$search.'%')
                ->orWhere('length', 'like', '%'.$search.'%');
        }


        $foCable = $data->paginate(self::$perPage);
        return self::formattedData($foCable);
    }


    public function formattedData(LengthAwarePaginator $foCable): LengthAwarePaginator
    {
        $data = $foCable->getCollection()->map(function ($item) {
            return [
                'id' => $item->id,
                'segment' => $item->segment_id,
                'classification' => $item->classification,
                'cable_placement' => $item->cable_placement,
                'total_core' => $item->total_core,
                'cable_address' => $item->cable_address,
                'coordinates_start_at' => $item->starting_point_lat.','.$item->starting_point_long,
                'coordinates_end_at' => $item->ending_point_lat.','.$item->ending_point_long,
                'length' => $item->length,
                'cut_off_date' => Carbon::parse($item->cut_off_date)->locale('id')
                    ->settings(['formatFunction' => 'translatedFormat'])
                    ->format('F Y'),
            ];
        });

        $foCable->setCollection($data);
        return $foCable;
    }

}