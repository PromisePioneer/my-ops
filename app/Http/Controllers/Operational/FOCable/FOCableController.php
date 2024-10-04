<?php

namespace App\Http\Controllers\Operational\FOCable;

use App\Http\Controllers\Controller;
use App\Http\Requests\FoCableRequest;
use App\Models\FOCable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FOCableController extends Controller
{
    public function index(): View
    {
        return view('pages.operational.fo-cables.index');
    }


    public function data(): JsonResponse
    {
        return response()->json(FOCable::paginate(10));
    }

    public function store(FoCableRequest $request): JsonResponse
    {
        FOCable::create($request->validated());
        return response()->json(['message' => 'Data berhasil disimpan']);
    }

    public function edit(FOCable $FOCable): JsonResponse
    {
        return response()->json($FOCable);
    }

    public function update(FoCableRequest $request, FOCable $FOCable): JsonResponse
    {
        $FOCable->segment_id = $request->input('segment_id');
        $FOCable->classification = $request->input('classification');
        $FOCable->cable_placement = $request->input('cable_placement');
        $FOCable->cable_address = $request->input('cable_address');
        $FOCable->total_core = $request->input('total_core');
        $FOCable->starting_point_lat = $request->input('starting_point_lat');
        $FOCable->starting_point_long = $request->input('starting_point_long');
        $FOCable->ending_point_lat = $request->input('ending_point_lat');
        $FOCable->ending_point_long = $request->input('ending_point_long');
        $FOCable->length = $request->input('length');
        $FOCable->cut_off_date = $request->input('cut_off_date');
        $FOCable->save();


        return response()->json(['message' => 'Data berhasil disimpan']);
    }

    public function destroy(Request $request, FOCable $FOCable): JsonResponse
    {
        $implodeID = implode(',', $request->get('id'));
        $explodeID = explode(',', $implodeID);
        $FOCable->whereIn('id', $explodeID)->delete();

        return response()->json([
            'message' => 'data berhasil dihapus',
        ], 200);
    }


    public function import()
    {
    }

    public function export()
    {
    }
}
