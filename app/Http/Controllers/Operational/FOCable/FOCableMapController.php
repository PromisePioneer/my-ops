<?php

namespace App\Http\Controllers\Operational\FOCable;

use App\Http\Controllers\Controller;
use App\Models\FOCable;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FOCableMapController extends Controller
{
    public function index(): View
    {
        return view('pages.operational.fo-cables.fo-cable-map.index');
    }

    public function data(): JsonResponse
    {
        $odpMap = FOCable::whereYear('cut_off_date', Carbon::now())->get();
        return response()->json($odpMap);
    }


    public function filter(Request $request): JsonResponse
    {
        $year = $request->input('year');
        $month = $request->input('month');

        $query = FOCable::orderBy('cut_off_date');


        if ($year && $month) {
            $query->whereMonth('cut_off_date', $month)->whereYear('cut_off_date', $year);
        }

        if ($year) {
            $query->whereYear('cut_off_date', $year);
        }

        if ($month) {
            $query->whereMonth('cut_off_date', $month);
        }


        $data = $query->get();

        return response()->json($data);
    }
}
