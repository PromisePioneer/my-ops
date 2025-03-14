<?php

namespace App\Support;

use App\Models\Pole;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class PoleService
{
    public function data(): LengthAwarePaginator
    {
        $data = Pole::with('branch')->paginate(10)->onEachSide(1);
        return self::formattedData($data);
    }


    public function search(Request $request): LengthAwarePaginator
    {
        $search = $request->input('search');
        $pole = Pole::with('branch');

        if (!empty($search)) {
            $pole->where(function ($query) use ($search) {
                $query->orWhere('diameter', 'like', '%'.$search.'%')
                    ->orWhereHas('branch', function ($query) use ($search) {
                        $query->where('name', 'like', '%'.$search.'%');
                    })->orWhere('length', 'like', '%'.$search.'%')
                    ->orWhere('region', 'like', '%'.$search.'%')
                    ->orWhere('code', 'like', '%'.$search.'%')
                    ->orWhere('lat', 'like', '%'.$search.'%')
                    ->orWhere('long', 'like', '%'.$search.'%');
            });
        }


        $data = $pole->paginate(10)->onEachSide(1);
        return self::formattedData($data);
    }


    public function formattedData(LengthAwarePaginator $poleData): LengthAwarePaginator
    {
        $data = $poleData->getCollection()->map(function ($item) {
            return [
                'id' => $item->id,
                'branch_name' => $item->branch->name,
                'diameter' => $item->diameter,
                'length' => $item->length,
                'region' => $item->region,
                'code' => $item->code,
                'coordinates' => $item->lat.','.$item->long,
                'cut_off_date' => Carbon::parse($item->cut_off_date)->locale('id')
                    ->settings(['formatFunction' => 'translatedFormat'])
                    ->format('F Y'),
            ];
        });

        $poleData->setCollection($data);
        return $poleData;
    }

}
