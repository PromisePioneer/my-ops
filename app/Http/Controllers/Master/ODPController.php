<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\ODPRequest;
use App\Models\ODP;
use App\Models\ODPArea;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ODPController extends Controller
{
    private ODPArea $ODPArea;

    public function __construct()
    {
        $this->ODPArea = new ODPArea();
    }


    public function index(): View
    {
        return view('pages.master.odp.index');
    }


    public function data(): JsonResponse
    {
        return response()->json(ODP::with('area')->paginate(10));
    }

    public function getODPAreaData(Request $request): JsonResponse
    {
        return response()->json($this->ODPArea->getData($request));
    }


    public function store(ODPRequest $request): JsonResponse
    {
        ODP::create($request->validated());
        return response()->json(['message' => 'Data ODP berhasil ditambahkan.']);
    }


    public function edit(ODP $odp): JsonResponse
    {
        return response()->json($odp);
    }


    public function getSelectedODPArea(ODP $odp): JsonResponse
    {
        return response()->json($this->ODPArea->getSelectedData($odp->area_id));
    }


    public function update(ODPRequest $request, ODP $odp): JsonResponse
    {
        $odp->update($request->validated());
        return response()->json(['message' => 'Data ODP berhasil diubah.']);
    }


    public function destroy(): JsonResponse
    {
    }
}
