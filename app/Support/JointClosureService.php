<?php

namespace App\Support;

use App\Models\JointClosure;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;

class JointClosureService
{

    private static int $perPage = 10;

    public function __construct()
    {
    }

    public function data()
    {
        $data = JointClosure::with('code', 'foCable')->paginate(self::$perPage);
        return self::formattedData($data);
    }

    public function formattedData(LengthAwarePaginator $jointClosures): LengthAwarePaginator
    {
        $data = $jointClosures->getCollection()->map(function ($item) {
            return [
                'id' => $item->id,
                'code' => $item->code_id,
                'region' => $item->region,
                'fo_cable' => $item->foCable->segment_id,
                'coordinates' => $item->lat.','.$item->long,
                'cut_off_date' => Carbon::parse($item->cut_off_date)->locale('id')->settings(
                    ['formatFunction' => 'translatedFormat']
                )->format('F Y'),
            ];
        });

        $jointClosures->setCollection($data);
        return $jointClosures;
    }

}
