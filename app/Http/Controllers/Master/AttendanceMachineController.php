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

    private Branch $branch;

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

    public function detail(AttendanceMachineInformation $attendanceMachineInformation): View
    {
        return view('pages.master.attendance-machine-info.detail', compact('attendanceMachineInformation'));
    }

    public function tarikDataAbsen(AttendanceMachineInformation $attendanceMachineInformation): void
    {
//        phpinfo();
        $IP = '203.153.21.78';
        $Key = 0;
        $Connect = fsockopen($IP, 4370, $errno, $errstr, 1);
        if ($Connect) {
            $soap_request = "<GetAttLog><ArgComKey xsi:type=\"xsd:integer\">".$Key."</ArgComKey><Arg><PIN xsi:type=\"xsd:integer\">All</PIN></Arg></GetAttLog>";
            $newLine = "\r\n";
            fputs($Connect, "POST /iWsService HTTP/1.0".$newLine);
            fputs($Connect, "Content-Type: text/xml".$newLine);
            fputs($Connect, "Content-Length: ".strlen($soap_request).$newLine.$newLine);
            fputs($Connect, $soap_request.$newLine);
            $buffer = '';
            while ($Response = fgets($Connect, (int) 20000000)) {
                $buffer = $buffer.$Response;
            }
        } else {
            dd('connect error');
        }

        $buffer = $this->parseData($buffer, "<GetAttLogResponse>", "</GetAttLogResponse>");
//        dd($buffer);
        $buffer = explode("\r\n", $buffer);
        for ($a = 0; $a < count($buffer); $a++) {
            $data = $this->parseData($buffer[$a], "<Row>", "</Row>");
            $PIN = $this->parseData($data, "<PIN>", "</PIN>");
            $DateTime = $this->parseData($data, "<DateTime>", "</DateTime>");
            $Verified = $this->parseData($data, "<Verified>", "</Verified>");
            $Status = $this->parseData($data, "<Status>", "</Status>");
        }
    }

    public function parseData($data, $p1, $p2)
    {
        $data = " ".$data;
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

    public function update(
        AttendanceMachineInformationRequest $request,
        AttendanceMachineInformation $attendanceMachineInformation
    ): JsonResponse {
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
