<?php

namespace App\Http\Controllers\ADMS;

use App\Http\Controllers\Controller;
use App\Models\Attendances;
use App\Models\DeviceLog;
use App\Models\ErrorLog;
use App\Models\FingerLog;
use App\Models\FpDevice;
use App\Models\ManageShift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class IclockController extends Controller
{
    /**
     * @throws Throwable
     */
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
                'online' => now()
            ]
        );

        return "GET OPTION FROM: {$request->input('SN')}\r\n".
            "Stamp=9999\r\n".
            "OpStamp=".time()."\r\n".
            "ErrorDelay=60\r\n".
            "Delay=30\r\n".
            "ResLogDay=18250\r\n".
            "ResLogDelCount=10000\r\n".
            "ResLogCount=50000\r\n".
            "TransTimes=00:00;14:05\r\n".
            "TransInterval=1\r\n".
            "TransFlag=1111000000\r\n".
            //  "TimeZone=7\r\n" .
            "Realtime=1\r\n".
            "Encrypt=0";
    }


    /**
     * @throws Throwable
     */
    public function receiveRecords(Request $request): void
    {
        DB::transaction(function () use ($request) {
            $officeHour = ManageShift::where('id', 1)->first();
            $content['url'] = json_encode($request->all());
            $content['data'] = $request->getContent();
            FingerLog::create($content);
            try {
                $arr = preg_split('/\\r\\n|\\r|,|\\n/', $request->getContent());
//                dd($arr);
                $tot = 0;
                if ($request->input('table') == "OPERLOG") {
                    foreach ($arr as $rey) {
                        if (isset($rey)) {
                            $tot++;
                        }
                    }
                    return "OK: ".$tot;
                }
                //attendance
                foreach ($arr as $rey) {
                    if (empty($rey)) {
                        continue;
                    }
//                    $data = explode("\t", $rey);
//                    $q['sn'] = $request->input('SN');
//                    $q['table'] = $request->input('table');
//                    $q['stamp'] = $request->input('Stamp');
//                    $q['employee_id'] = $data[0];
//                    $q['timestamp'] = $data[1];
//                    $q['status1'] = $this->validateAndFormatInteger($data[2] ?? null);

                    $statusCheck = 0;
                    if ($this->validateAndFormatInteger($data[2] ?? null) === 1) {
                        $statusCheck = 1;
                    }


                    $test = Attendances::updateOrCreate([
                        'employee_id' => $data[0],
                    ], [
                        'sn' => $request->input('SN'),
                        'table' => $request->input('table'),
                        'stamp' => $request->input('Stamp'),
                        'timestamp' => $data[1],
                        'check_in' => $statusCheck,
                    ]);
                    dd($test);
                    $tot++;
                }
                return "OK: ".$tot;
            } catch (Throwable $e) {
                $data['error'] = $e;
                ErrorLog::create(($data));
                report($e);
                return "ERROR: ".$tot."\n";
            }
        });
    }

    private function validateAndFormatInteger($value): ?int
    {
        return isset($value) && $value !== '' ? (int) $value : null;
    }

    public function test(Request $request): void
    {
        $log['data'] = $request->getContent();
        FingerLog::create($log);
    }

    public function getrequest(Request $request): string
    {
        //  $r = "GET OPTION FROM: ".$request->SN."\nStamp=".strtotime('now')."\nOpStamp=".strtotime('now')."\nErrorDelay=60\nDelay=30\nResLogDay=18250\nResLogDelCount=10000\nResLogCount=50000\nTransTimes=00:00;14:05\nTransInterval=1\nTransFlag=1111000000\nRealtime=1\nEncrypt=0";
        return "OK";
    }
}
