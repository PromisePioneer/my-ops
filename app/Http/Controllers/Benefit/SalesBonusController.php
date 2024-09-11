<?php

namespace App\Http\Controllers\Benefit;

use App\Http\Controllers\Controller;
use App\Http\Requests\Benefit\SalesBonusRequest;
use App\Models\BroadbandPacket;
use App\Models\SaleBonus;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SalesBonusController extends Controller
{

    private User $user;
    private SaleBonus $saleBonus;
    private BroadbandPacket $broadbandPacket;

    public function __construct()
    {
        $this->user = new User();
        $this->saleBonus = new SaleBonus();
        $this->broadbandPacket = new BroadbandPacket();
    }

    public function index()
    {
        return view('pages.payroll.benefit.sales-bonus.index');
    }


    public function getUserData(Request $request): JsonResponse
    {
        return response()->json($this->user->getUser($request));
    }

    public function getSelectedUser(SaleBonus $saleBonus): JsonResponse
    {
        return response()->json($this->user->getSelectedData($saleBonus->user_id));
    }


    public function getBroadbandPacket(Request $request): JsonResponse
    {
        return response()->json($this->broadbandPacket->getData($request));
    }


    public function getSelectedBroadbandPacket(SaleBonus $saleBonus): JsonResponse
    {
        return response()->json($this->broadbandPacket->getSelectedData($saleBonus->packet_id));
    }


    public function data(): JsonResponse
    {
        return response()->json($this->saleBonus->data());
    }

    public function search()
    {
    }

    public function store(SalesBonusRequest $request): JsonResponse
    {
        $percentageBonus = 20;
        $packet = BroadbandPacket::where('id', $request->packet_id)->first();
        $data = $request->validated();
        $discount = (int) $request->discount;
        $calculatePacketDiscount = ($packet->price / 100) * $discount;
        if ($request->discount) {
            $data['amount'] = ($calculatePacketDiscount / 100) * $percentageBonus;
        } else {
            $data['amount'] = $packet->price / 100 * $percentageBonus;
        }

        SaleBonus::create($data);
        return response()->json([
            'message' => 'data berhasil disimpan',
        ]);
    }


    public function edit(SaleBonus $saleBonus): JsonResponse
    {
        return response()->json($saleBonus);
    }


    public function update(SalesBonusRequest $request, SaleBonus $saleBonus): JsonResponse
    {
        $percentageBonus = 20;
        $packet = BroadbandPacket::where('id', $request->packet_id)->first();
        $data = $request->validated();
        $discount = (int) $request->discount;
        $calculatePacketDiscount = ($packet->price / 100) * $discount;
        if ($request->discount) {
            $data['amount'] = ($calculatePacketDiscount / 100) * $percentageBonus;
        } else {
            $data['amount'] = $packet->price / 100 * $percentageBonus;
        }

        $saleBonus->update($data);
        return response()->json([
            'message' => 'data berhasil disimpan',
        ]);
    }

    public function destroy(Request $request, SaleBonus $saleBonus): JsonResponse
    {
        $implodeID = implode(',', $request->get('id'));
        $explodeID = explode(',', $implodeID);
        $saleBonus->whereIn('id', $explodeID)->delete();

        return response()->json([
            'message' => 'Data sukses dihapus.',
        ]);
    }
}
