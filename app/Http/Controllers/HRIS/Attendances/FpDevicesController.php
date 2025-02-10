<?php

namespace App\Http\Controllers\HRIS\Attendances;

use AllowDynamicProperties;
use App\Http\Controllers\Controller;
use App\Http\Requests\ADMS\FpDeviceRequest;
use App\Jobs\AttendanceJob;
use App\Models\Branch;
use App\Models\FpDevice;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Jmrashed\Zkteco\Lib\ZKTeco;

#[AllowDynamicProperties] class FpDevicesController extends Controller
{
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

    public function data(Request $request): JsonResponse
    {
        $data = FpDevice::with('branch')
            ->when($request->user()->hasRole('Branch Manager'), function ($query) use ($request) {
                $query->where('branch_id', $request->user()->branch_id);
            })
            ->paginate(self::$perPage);
        return response()->json($data);
    }

    public function search(Request $request): JsonResponse
    {
        $search = $request->input('search');

        $data = FpDevice::with('branch')
            ->when(!empty($search), function ($query) use ($search) {
                $query->whereHas('branch', function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%');
                })->orWhere('name', 'like', '%' . $search . '%')
                    ->orWhere('serial_number', 'like', '%' . $search . '%')
                    ->orWhere('ip_address', 'like', '%' . $search . '%');
            })->paginate(self::$perPage);

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


    public function testConnection(FpDevice $fpDevice): JsonResponse
    {
        ini_set("max_execution_time", 1);
        $zk = new ZKTeco($fpDevice->ip_address, 4370);
        $connected = $zk->connect();
        if ($connected) {
            return response()->json(['message' => 'Koneksi Sukses']);
        } else {
            return response()->json(['message' => 'Koneksi Gagal'], 500);
        }
    }


    public function getAttendances(Request $request, FpDevice $fpDevice): JsonResponse
    {

        $zk = new ZKTeco($fpDevice->ip_address, 4370);
        $connected = $zk->connect();
        if ($connected) {
            AttendanceJob::dispatch($fpDevice, $request->start_date, $request->end_date);
        } else {
            return response()->json([
                'message' => 'Koneksi Gagal',
            ], 500);
        }
        return response()->json([
            'message' => 'Attendance processing has been queued and will be processed in the background.',
        ]);
    }


    public function restartDevice(FpDevice $fpDevice): JsonResponse
    {
        $zk = new ZKTeco($fpDevice->ip_address, 4370);
        $connected = $zk->connect();
        if (!$connected) {
            return response()->json(['message' => 'Koneksi ke mesin gagal'], 500);
        }
        $zk->restart();
        return response()->json(['message' => 'Mesin berhasil direstart.']);
    }
}
