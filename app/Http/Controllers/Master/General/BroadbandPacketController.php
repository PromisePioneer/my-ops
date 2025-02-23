<?php

namespace App\Http\Controllers\Master\General;

use AllowDynamicProperties;
use App\Http\Controllers\Controller;
use App\Http\Requests\BroadbandPacketRequest;
use App\Models\Branch;
use App\Models\BroadbandPacket;
use App\Service\Master\General\BroadbandPacketService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

#[AllowDynamicProperties] class BroadbandPacketController extends Controller
{
    public function __construct()
    {
        $this->branch = new Branch();
        $this->broadbandPacket = new BroadbandPacket();
        $this->broadbandPacketService = new BroadbandPacketService();
    }

    /**
     * @throws AuthorizationException
     */
    public function index(): View
    {
        $this->authorize('view', BroadbandPacket::class);
        return view('pages.general-master-data.broadband-packets.index');
    }

    /**
     * @throws AuthorizationException
     */
    public function data(Request $request): JsonResponse
    {
        $this->authorize('view', BroadbandPacket::class);
        return response()->json($this->broadbandPacketService->data($request));
    }
    /**
     * @throws AuthorizationException
     */
    public function search(Request $request): JsonResponse
    {
        $this->authorize('view', BroadbandPacket::class);
        return response()->json($this->broadbandPacketService->search($request));
    }


    public function filter(Request $request): JsonResponse
    {
        return response()->json($this->broadbandPacketService->filter($request));
    }


    public function branchData(Request $request): JsonResponse
    {
        return response()->json($this->branch->getData($request));
    }

    /**
     * @throws AuthorizationException
     */
    public function store(BroadbandPacketRequest $request): JsonResponse
    {
        $this->authorize('create', BroadbandPacket::class);
        $data = $request->validated();
        BroadbandPacket::create($data);
        return response()->json([
            'message' => 'data berhasil disimpan',
        ]);
    }


    /**
     * @throws AuthorizationException
     */
    public function edit(BroadbandPacket $broadbandPacket): JsonResponse
    {
        $this->authorize('update', $broadbandPacket);
        return response()->json($broadbandPacket);
    }


    public function selectedBranch(BroadbandPacket $broadbandPacket)
    {
        return response()->json($this->branch->getSelectedData($broadbandPacket->branch_id));
    }


    /**
     * @throws AuthorizationException
     */
    public function update(BroadbandPacketRequest $request, BroadbandPacket $broadbandPacket): JsonResponse
    {
        $this->authorize('update', $broadbandPacket);
        $data = $request->validated();
        $broadbandPacket->update($data);
        return response()->json([
            'message' => 'data berhasil disimpan',
        ]);
    }


    /**
     * @throws AuthorizationException
     */
    public function destroy(Request $request, BroadbandPacket $broadbandPacket): JsonResponse
    {
        $this->authorize('delete', $broadbandPacket);
        $implodeID = implode(',', $request->get('id'));
        $explodeID = explode(',', $implodeID);
        $broadbandPacket->whereIn('id', $explodeID)->delete();

        return response()->json([
            'message' => 'data berhasil dihapus',
        ]);
    }

}
