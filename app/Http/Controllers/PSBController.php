<?php

namespace App\Http\Controllers;

use AllowDynamicProperties;
use App\Http\Requests\PSBRequest;
use App\Models\Area;
use App\Models\BroadbandPacket;
use App\Models\PSB;
use App\Service\PSBService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

#[AllowDynamicProperties] class PSBController extends Controller
{

    public function __construct()
    {
        $this->psbService = new PSBService();
        $this->area = new Area();
        $this->broadbandPacket = new BroadbandPacket();
    }

    public function index(): View
    {
        return view('pages.operational-master-data.psb.index');
    }

    public function data(): JsonResponse
    {
        return response()->json($this->psbService->data());
    }

    public function getAreaData(Request $request): JsonResponse
    {
        return response()->json($this->area->getData($request));
    }
    public function store(PSBRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['pic'] = $request->user()->id;
        PSB::create($data);
        return response()->json([
            'message' => 'data berhasil disimpan'
        ]);
    }

    public function edit(PSB $psb): JsonResponse
    {
        return response()->json($psb);
    }

    public function update(PSBRequest $request, PSB $psb): JsonResponse
    {
        $psb->update($request->validated());
        return response()->json([
            'message' => 'data berhasil disimpan'
        ]);
    }


    public function destroy(PSB $psb, Request $request): JsonResponse
    {
        $implodeID = implode(',', $request->get('id'));
        $explodeID = explode(',', $implodeID);
        $psb->whereIn('id', $explodeID)->delete();

        return response()->json([
            'message' => 'data berhasil dihapus'
        ]);
    }
}
