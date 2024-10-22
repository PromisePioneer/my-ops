<?php

namespace App\Http\Controllers\Inventory\ODP;

use App\Http\Controllers\Controller;
use App\Models\ODP;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ODPMapController extends Controller
{
    public function index(): View
    {
        return view('pages.operational.odp.odp-map.index');
    }

    public function data(): JsonResponse
    {
        $odpMap = ODP::whereYear('created_at', Carbon::now())->get();
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
