<?php

namespace App\Http\Controllers\Master\Operational;

use AllowDynamicProperties;
use App\Http\Controllers\Controller;
use App\Http\Requests\PSBRequest;
use App\Models\Area;
use App\Models\BroadbandPacket;
use App\Models\PSB;
use App\Service\PSBService;
use Illuminate\Auth\Access\AuthorizationException;
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
        $this->authorize('view', PSB::class);
        return view('pages.operational-master-data.psb.index');
    }

    /**
     * @throws AuthorizationException
     */
    public function data(): JsonResponse
    {
        $this->authorize('view', PSB::class);
        return response()->json($this->psbService->data());
    }

    /**
     * @throws AuthorizationException
     */
    public function getAreaData(Request $request): JsonResponse
    {
        $this->authorize('view', PSB::class);
        return response()->json($this->area->getData($request));
    }

    /**
     * @throws AuthorizationException
     */
    public function store(PSBRequest $request): JsonResponse
    {
        $this->authorize('create', PSB::class);
        $data = $request->validated();
        $data['pic'] = $request->user()->id;
        PSB::create($data);
        return response()->json([
            'message' => 'data berhasil disimpan'
        ]);
    }

    /**
     * @throws AuthorizationException
     */
    public function edit(PSB $psb): JsonResponse
    {
        $this->authorize('edit', PSB::class);
        return response()->json($psb);
    }

    /**
     * @throws AuthorizationException
     */
    public function update(PSBRequest $request, PSB $psb): JsonResponse
    {
        $this->authorize('update', PSB::class);
        $psb->update($request->validated());
        return response()->json([
            'message' => 'data berhasil disimpan'
        ]);
    }


    /**
     * @throws AuthorizationException
     */
    public function destroy(PSB $psb, Request $request): JsonResponse
    {
        $this->authorize('delete', PSB::class);
        $implodeID = implode(',', $request->get('id'));
        $explodeID = explode(',', $implodeID);
        $psb->whereIn('id', $explodeID)->delete();

        return response()->json([
            'message' => 'data berhasil dihapus'
        ]);
    }
}
