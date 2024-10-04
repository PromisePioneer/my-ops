<?php

namespace App\Http\Controllers\Operational\Pole;

use App\Http\Controllers\Controller;
use App\Models\ODP;
use App\Models\Pole;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PoleMapController extends Controller
{

    public function index(): View
    {
        return view('pages.operational.poles.pole-map.index');
    }

    public function data(): JsonResponse
    {
        $odpMap = Pole::whereYear('cut_off_date', Carbon::now())->get();
        return response()->json($odpMap);
    }


    public function filter(Request $request): JsonResponse
    {
        $year = $request->input('year');
        $month = $request->input('month');

        $query = ODP::orderBy('cut_off_date');

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
