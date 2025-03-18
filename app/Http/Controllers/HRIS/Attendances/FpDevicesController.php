<?php

namespace App\Http\Controllers\HRIS\Attendances;

use AllowDynamicProperties;
use App\Http\Controllers\Controller;
use App\Http\Requests\ADMS\FpDeviceRequest;
use App\Jobs\AttendanceJob;
use App\Models\AttendanceJobProgress;
use App\Models\FpDevice;
use App\Models\Master\Common\Branch;
use App\Support\FpDevice\FpDeviceService;
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
        $this->fpDeviceService = new FpDeviceService();
    }

    public function index(): View
    {
        return view('pages.adms.fp-devices.index');
    }

    public function data(Request $request): JsonResponse
    {
        return response()->json($this->fpDeviceService->data($request));
    }

    public function search(Request $request): JsonResponse
    {
        return response()->json($this->fpDeviceService->search($request));
    }

    public function filter(Request $request): JsonResponse
    {
        return response()->json($this->fpDeviceService->filter($request));
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
        $zk = new ZKTeco($fpDevice->ip_address, 4370);
        $connected = $zk->connect();
        if ($connected) {
            $zk->disconnect();
            return response()->json(['message' => 'Koneksi Sukses']);
        } else {
            return response()->json(['message' => 'Koneksi Gagal'], 500);
        }
    }


    public function getAttendances(Request $request, FpDevice $fpDevice): JsonResponse
    {
        AttendanceJob::dispatch($fpDevice, $request->start_date, $request->end_date);
        return response()->json([
            'message' => 'Attendance processing has been queued and will be processed in the background.',
        ]);
    }

    public function getUser(FpDevice $fpDevice)
    {
        $zk = new ZKTeco($fpDevice->ip_address, 4370);
        $connected = $zk->connect();

        if ($connected) {
            $users = $zk->getUser();
            $fpTemplate = [];

            foreach ($users as $user) {
                $uid = $user['uid'];
                $fingerprintData = $zk->getFingerprint($uid);

                $fpTemplate[] = [
                    'user' => $user,
                    'fingerprint' => $fingerprintData
                ];
            }
            return response()->json(mb_convert_encoding($fpTemplate, 'UTF-8', 'UTF-8'));
        }
        return response()->json(['message' => 'error']);
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


    public function getJobStatus(FpDevice $fpDevice): JsonResponse
    {
        $progress = AttendanceJobProgress::where('device_id', $fpDevice->id)
            ->latest()
            ->first();


        if (!$progress) {
            return response()->json(['message' => 'No job found for this device.'], 404);
        }

        return response()->json([
            'status' => $progress->status,
            'error_message' => $progress->error_message,
        ]);
    }
}
