<?php

namespace App\Http\Controllers\ADMS;

use App\Http\Controllers\Controller;
use App\Http\Requests\ADMS\FpDeviceRequest;
use App\Models\Branch;
use App\Models\FpDevice;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FpDevicesController extends Controller
{

    public readonly int $perPage;

    public function __construct()
    {
        $this->perPage = 10;
        $this->FpDevices = new FpDevice();
        $this->branch = new Branch();
    }

    public function index(): View
    {
        return view('pages.adms.fp-devices.index');
    }

    public function data(): JsonResponse
    {
        return response()->json($this->FpDevices->getDataWithPagination($this->perPage));
    }

    public function search(Request $request): JsonResponse
    {
        return response()->json($this->FpDevices->searchData($request));
    }

    public function getBranchData(Request $request): JsonResponse
    {
        return response()->json($this->branch->getData($request));
    }

    public function selectedBranchData(FpDevice $fpDevice): JsonResponse
    {
        return response()->json($this->branch->getSelectedData($fpDevice->branch_id));
    }

    public function store(FpDeviceRequest $request): JsonResponse
    {
        FpDevice::create($request->validated());
        return response()->json([
            'message' => 'data berhasil disimpan'
        ]);
    }

    public function edit(FpDevice $fpDevice): JsonResponse
    {
        return response()->json($fpDevice);
    }


    public function update(FpDeviceRequest $request, FpDevice $fpDevice): JsonResponse
    {
        $fpDevice->update($request->validated());
        return response()->json([
            'message' => 'data berhasil disimpan'
        ]);
    }

    public function destroy(FpDevice $fpDevice)
    {
        $fpDevice->delete();
        return response()->json([
            'message' => 'data berhasil dihapus'
        ]);
    }
}
