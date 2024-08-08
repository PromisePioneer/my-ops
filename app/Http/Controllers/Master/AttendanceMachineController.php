<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\AttendanceMachineInformation\AttendanceMachineInformationRequest;
use App\Models\AttendanceMachineInformation;
use App\Models\Branch;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use JetBrains\PhpStorm\NoReturn;
use Jmrashed\Zkteco\Lib\ZKTeco;

class AttendanceMachineController extends Controller
{
    public int $perPage = 10;

    private AttendanceMachineInformation $attendanceMachineInformation;

    public function __construct()
    {
        $this->attendanceMachineInformation = new AttendanceMachineInformation;
        $this->branch = new Branch;
    }

    public function index(): View
    {
        return view('pages.master.attendance-machine-info.index');
    }

    public function data(): JsonResponse
    {
        return response()->json($this->attendanceMachineInformation->getDataWithPagination($this->perPage));
    }

    public function getBranchData(Request $request): JsonResponse
    {
        return response()->json($this->branch->getData($request));
    }

    public function search(Request $request): JsonResponse
    {
        return response()->json($this->attendanceMachineInformation->searchData($request));
    }

    public function store(AttendanceMachineInformationRequest $request): JsonResponse
    {
        AttendanceMachineInformation::create($request->validated());

        return response()->json([
            'message' => 'data berhasil disimpan',
        ]);
    }

    public function getSelectedBranch(AttendanceMachineInformation $attendanceMachineInformation): JsonResponse
    {
        return response()->json($this->branch->getSelectedData($attendanceMachineInformation->branch_id));
    }

    public function edit(AttendanceMachineInformation $attendanceMachineInformation): JsonResponse
    {
        return response()->json($attendanceMachineInformation);
    }

    public function detail(AttendanceMachineInformation $attendanceMachineInformation)
    {
        return view('pages.master.attendance-machine-info.detail', compact('attendanceMachineInformation'));
    }

    #[NoReturn]
    public function tarikDataAbsen(AttendanceMachineInformation $attendanceMachineInformation): void
    {
        $zk = new ZKTeco('210.87.122.240');
        $zk->connect();

        $attendanceLog = $zk->getAttendance();
        dd($attendanceLog);
    }

    private function parseData($data, $p1, $p2): string
    {
        $data = ' '.$data;
        $hasil = '';
        $awal = strpos($data, $p1);
        if ($awal != '') {
            $akhir = strpos(strstr($data, $p1), $p2);
            if ($akhir != '') {
                $hasil = substr($data, ($awal + strlen($p1)), ($akhir - strlen($p1)));
            }
        }

        return $hasil;
    }

    public function update(AttendanceMachineInformationRequest $request, AttendanceMachineInformation $attendanceMachineInformation): JsonResponse
    {
        $attendanceMachineInformation->update($request->validated());

        return response()->json([
            'message' => 'data berhasil disimpan',
        ]);
    }

    public function destroy(AttendanceMachineInformation $attendanceMachineInformation): JsonResponse
    {
        $attendanceMachineInformation->delete();

        return response()->json([
            'message' => 'data berhasil disimpan',
        ]);
    }
}
