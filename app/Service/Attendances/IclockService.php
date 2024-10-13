<?php

namespace App\Service\Attendances;

use App\Models\Attendances;
use App\Models\DeviceLog;
use App\Models\FingerLog;
use App\Models\FpDevice;
use App\Models\UserWorkTime;
use App\Models\WorkTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class IclockService
{
    public function handshake(Request $request): string
    {
        $data = [
            'url' => json_encode($request->all()),
            'data' => $request->getContent(),
            'serial_number' => $request->input('SN'),
            'option' => $request->input('option'),
        ];
        DeviceLog::create($data);

        // update status device
        FpDevice::updateOrCreate(
            ['serial_number' => $request->input('SN')],
            [
                'name' => $request->input('SN'),
                'online' => now(),
            ]
        );

        return "GET OPTION FROM: {$request->input('SN')}\r\n".
            "Stamp=9999\r\n".
            'OpStamp='.time()."\r\n".
            "ErrorDelay=60\r\n".
            "Delay=30\r\n".
            "ResLogDay=18250\r\n".
            "ResLogDelCount=10000\r\n".
            "ResLogCount=50000\r\n".
            "TransTimes=00:00;14:05\r\n".
            "TransInterval=1\r\n".
            "TransFlag=1111000000\r\n".
            "TimeZone=7\r\n".
            "Realtime=1\r\n".
            'Encrypt=0';
    }

    public function recieveRecords(Request $request): string
    {
        try {
            $arr = preg_split('/\\r\\n|\\r|,|\\n/', $request->getContent());
            $tot = 0;
            $this->handleOperLog($request, $arr, $tot);
            foreach ($arr as $rey) {
                if (empty($rey)) {
                    continue;
                }
                $data = explode("\t", $rey);
                Attendances::create([
                    'sn' => $request->input('SN'),
                    'table' => $request->input('table'),
                    'stamp' => $request->input('Stamp'),
                    'employee_id' => $data[0],
                    'timestamp' => $data[1],
                    'status1' => $this->validateAndFormatInteger($data[2] ?? null),
                ]);
                $tot++;
            }
            return "OK: ".$tot;
        } catch (Throwable $e) {
            report($e);
            return "ERROR: ".$e."\n";
        }
    }


    public function getuserShift()
    {

    }


    public function handleOperLog(Request $request, $arr, $tot): string
    {
        if ($request->input('table') == "OPERLOG") {
            // $tot = count($arr) - 1;
            foreach ($arr as $rey) {
                if (isset($rey)) {
                    $tot++;
                }
            }
            return "OK: ".$tot;
        }
        return "";
    }


    private function validateAndFormatInteger($value): ?int
    {
        return isset($value) && $value !== '' ? (int)$value : null;
    }

}
