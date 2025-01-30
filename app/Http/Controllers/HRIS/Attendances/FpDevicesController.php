<?php

namespace App\Http\Controllers\HRIS\Attendances;

use App\Http\Controllers\Controller;
use App\Http\Requests\ADMS\FpDeviceRequest;
use App\Models\Branch;
use App\Models\FpDevice;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FpDevicesController extends Controller
{

    private FpDevice $FpDevices;
    private Branch $branch;
    private static int $perPage = 10;
    public function __construct()
    {
        $this->FpDevices = new FpDevice();
        $this->branch = new Branch();
    }

    public function index(): View
    {
        return view('pages.adms.fp-devices.index');
    }

    public function data(): JsonResponse
    {
        return response()->json(FpDevice::with('branch')->paginate(self::$perPage));
    }

    public function search(Request $request): JsonResponse
    {
        $search = $request->input('search');

        $data = FpDevice::with('branch')
            ->when(!empty($search), function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->where('serial_number', 'like', '%' . $search . '%');
            })
            ->paginate(self::$perPage);
        return response()->json($data);
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
            'message' => 'data berhasil disimpan',
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
            'message' => 'data berhasil disimpan',
        ]);
    }

    public function destroy(Request $request, FpDevice $fpDevice): JsonResponse
    {
        $implodeID = implode(',', $request->get('id'));
        $explodeID = explode(',', $implodeID);
        $fpDevice->whereIn('id', $explodeID)->delete();

        return response()->json([
            'message' => 'data berhasil dihapus',
        ], 200);
    }
}
