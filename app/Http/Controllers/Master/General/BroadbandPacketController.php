<?php

namespace App\Http\Controllers\Master\General;

use App\Http\Controllers\Controller;
use App\Http\Requests\BroadbandPacketRequest;
use App\Models\BroadbandPacket;
use App\Service\Master\BroadbandPacketService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BroadbandPacketController extends Controller
{

    private BroadbandPacket $broadbandPacket;
    private BroadbandPacketService $broadbandPacketService;

    public function __construct()
    {
        $this->broadbandPacket = new BroadbandPacket();
        $this->broadbandPacketService = new BroadbandPacketService();
    }

    /**
     * @throws AuthorizationException
     */
    public function index(): View
    {
        $this->authorize('view', BroadbandPacket::class);
        return view('pages.general-master-data.broadband-packet.index');
    }

    /**
     * @throws AuthorizationException
     */
    public function search(Request $request): JsonResponse
    {
        $this->authorize('view', BroadbandPacket::class);
        $search = $request->input('search');
        $query = $this->broadbandPacket->data($request);

        if (!empty($search)) {
            $query->where('name', 'like', '%'.$search.'%')->orWhereHas('branch', function ($query) use ($search) {
                $query->where('name', 'like', '%'.$search.'%');
            })->orWhere('capacity', 'like', '%'.$search.'%');
        }

        $data = $query->paginate(10);
        return response()->json($data);
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
    public function store(BroadbandPacketRequest $request): JsonResponse
    {
        $this->authorize('create', BroadbandPacket::class);
        $data = $request->validated();
        $data['branch_id'] = $request->user()->branch_id;
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


    /**
     * @throws AuthorizationException
     */
    public function update(BroadbandPacketRequest $request, BroadbandPacket $broadbandPacket): JsonResponse
    {
        $this->authorize('update', $broadbandPacket);
        $data = $request->validated();
        $data['branch_id'] = $request->user()->branch_id;
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
