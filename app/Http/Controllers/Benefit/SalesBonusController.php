<?php

namespace App\Http\Controllers\Benefit;

use App\Http\Controllers\Controller;
use App\Http\Requests\Benefit\SalesBonusRequest;
use App\Http\Requests\SalesBonusImportRequest;
use App\Imports\SalesBonusImport;
use App\Models\BroadbandPacket;
use App\Models\SaleBonus;
use App\Models\User;
use App\Service\SalesBonus\SalesBonusService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class SalesBonusController extends Controller
{

    private static int $bonusPercentage = 20;
    private User $user;
    private SaleBonus $saleBonus;
    private BroadbandPacket $broadbandPacket;
    private SalesBonusService $salesBonusService;


    public function __construct()
    {
        $this->user = new User();
        $this->saleBonus = new SaleBonus();
        $this->broadbandPacket = new BroadbandPacket();
        $this->salesBonusService = new SalesBonusService();
    }

    public function index(): View
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
        return response()->json($this->salesBonusService->data());
    }

    public function search(Request $request): JsonResponse
    {
        return response()->json($this->salesBonusService->search($request));
    }

    public function store(SalesBonusRequest $request): JsonResponse
    {
        $this->salesBonusService->store($request);
        return response()->json(['message' => 'data berhasil disimpan']);
    }


    public function edit(SaleBonus $saleBonus): JsonResponse
    {
        return response()->json($saleBonus);
    }


    public function update(SalesBonusRequest $request, SaleBonus $saleBonus): JsonResponse
    {
        $this->salesBonusService->update($request, $saleBonus);
        return response()->json(['message' => 'data berhasil disimpan']);
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


    public function import(SalesBonusImportRequest $request): JsonResponse
    {
        $file = $request->file('file_import');
        Excel::import(new SalesBonusImport(), $file);

        return response()->json([
            'message' => 'Data berhasil diimport',
        ]);
    }
}
