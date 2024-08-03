<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\AttendanceMachineInformation\AttendanceMachineInformationRequest;
use App\Models\AttendanceMachineInformation;
use App\Models\Branch;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AttendanceMachineController extends Controller
{
    public int $perPage = 10;
    private AttendanceMachineInformation $attendanceMachineInformation;

    public function __construct()
    {
        $this->attendanceMachineInformation = new AttendanceMachineInformation();
        $this->branch = new Branch();
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
            'message' => 'data berhasil disimpan'
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

    public function tarikDataAbsen(AttendanceMachineInformation $attendanceMachineInformation): void
    {
        $ip = $attendanceMachineInformation->ip_address;
        $port = $attendanceMachineInformation->port;
        $key = $attendanceMachineInformation->key;

        $Connect = fsockopen($ip, $port, $errno, $errstr, 1);
        if ($Connect) {
            $soap_request = "<GetAttLog>
                                <ArgComKey xsi:type=\"xsd:integer\">" . $key . "</ArgComKey>
                                <Arg><PIN xsi:type=\"xsd:integer\">All</PIN></Arg>
                              </GetAttLog>";

            $newLine = "\r\n";
            fwrite($Connect, "POST /iWsService HTTP/1.0" . $newLine);
            fwrite($Connect, "Content-Type: text/xml" . $newLine);
            fwrite($Connect, "Content-Length: " . strlen($soap_request) . $newLine . $newLine);
            fwrite($Connect, $soap_request . $newLine);
            $buffer = "";
            while ($Response = fgets($Connect, 1024)) {
                $buffer .= $Response;
            }
        } else {
            echo "Koneksi Gagal";
        }

        $buffer = $this->parseData($buffer, "<GetAttLogResponse>", "</GetAttLogResponse>");
        $buffer = explode("\r\n", $buffer);

        for ($a = 0, $aMax = count($buffer); $a < $aMax; $a++) {
            $data = $this->parseData($buffer[$a], "<Row>", "</Row>");

            $export[$a]['pin'] = $this->parseData($data, "<PIN>", "</PIN>");
            $export[$a]['waktu'] = $this->parseData($data, "<DateTime>", "</DateTime>");
            $export[$a]['status'] = $this->parseData($data, "<Status>", "</Status>");
        }

        echo '<pre>';
        print_r($export);
    }

    function parseData($data, $p1, $p2)
    {
        $data = " " . $data;
        $hasil = "";
        $awal = strpos($data, $p1);
        if ($awal != "") {
            $akhir = strpos(strstr($data, $p1), $p2);
            if ($akhir != "") {
                $hasil = substr($data, $awal + strlen($p1), $akhir - strlen($p1));
            }
        }
        return $hasil;
    }

    public function update(AttendanceMachineInformationRequest $request, AttendanceMachineInformation $attendanceMachineInformation): JsonResponse
    {
        $attendanceMachineInformation->update($request->validated());

        return response()->json([
            'message' => 'data berhasil disimpan'
        ]);
    }


    public function destroy(AttendanceMachineInformation $attendanceMachineInformation): JsonResponse
    {
        $attendanceMachineInformation->delete();
        return response()->json([
            'message' => 'data berhasil disimpan'
        ]);
    }
}
