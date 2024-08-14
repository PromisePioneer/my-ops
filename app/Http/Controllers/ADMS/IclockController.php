<?php

namespace App\Http\Controllers\ADMS;

use App\Http\Controllers\Controller;
use App\Models\DeviceLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class IclockController extends Controller
{
    /**
     * @throws Throwable
     */
    private readonly string $sn;

    public function __construct()
    {
        $this->sn = 'AEWD233960062';
    }


    public function handshake(Request $request)
    {
        $response = "GET OPTION FROM: {$this->sn}\r\n".
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

        DB::transaction(function () use ($request, &$response) {
            $data = [
                'url' => "http://www.solutioncloud.co.id/iclock/edata?SN=$this->sn&table=ATTLOG&Stamp=9999",
                'data' => $request->getContent(),
                'serial_number' => $this->sn,
                'option' => &$response,
            ];
            DeviceLog::create($data);

            DB::table('fp_devices')->updateOrInsert(
                ['serial_number' => $this->sn],
                ['name' => 'mantap']
            );
        });


        return response()->json($response);
    }


    public function receiveRecords(Request $request): string
    {
//        dd($request->all());
        //DB::connection()->enableQueryLog();
        $url = "http://www.solutioncloud.co.id/iclock/edata?SN=$this->sn&table=ATTLOG&Stamp=9999";
        $content = file_get_contents($url);
        dd($content);
//        $content['url'] = "http://www.solutioncloud.co.id/iclock/edata?SN=$this->sn&table=ATTLOG&Stamp=9999";
//        $content['data'] = ;
//        DB::table('finger_log')->insert($content);
//        try {
//            // $post_content = $request->getContent();
//            //$arr = explode("\n", $post_content);
//            $arr = preg_split('/\\r\\n|\\r|,|\\n/', $request->getContent());
//            dd($arr);
//            //$tot = count($arr);
//            $tot = 0;
//            //operation log
//            if ($request->input('table') == "OPERLOG") {
//                // $tot = count($arr) - 1;
//                foreach ($arr as $rey) {
//                    if (isset($rey)) {
//                        $tot++;
//                    }
//                }
//                return "OK: ".$tot;
//            }
//            //attendance
//            foreach ($arr as $rey) {
//                // $data = preg_split('/\s+/', trim($rey));
//                if (empty($rey)) {
//                    continue;
//                }
//                // $data = preg_split('/\s+/', trim($rey));
//                $data = explode("\t", $rey);
//                //dd($data);
//                $q['sn'] = $this->sn;
//                $q['table'] = $request->input('table');
//                $q['stamp'] = $request->input('Stamp');
//                $q['employee_id'] = $data[0];
//                $q['timestamp'] = $data[1];
//                $q['status1'] = $this->validateAndFormatInteger($data[2] ?? null);
//                $q['status2'] = $this->validateAndFormatInteger($data[3] ?? null);
//                $q['status3'] = $this->validateAndFormatInteger($data[4] ?? null);
//                $q['status4'] = $this->validateAndFormatInteger($data[5] ?? null);
//                $q['status5'] = $this->validateAndFormatInteger($data[6] ?? null);
//                $q['created_at'] = now();
//                $q['updated_at'] = now();
//                //dd($q);
//                DB::table('attendances')->insert($q);
//                $tot++;
//                // dd(DB::getQueryLog());
//            }
//            return "OK: ".$tot;
//        } catch (Throwable $e) {
//            $data['error'] = $e;
//            DB::table('error_log')->insert($data);
//            report($e);
//            return "ERROR: ".$tot."\n";
//        }
    }

    public function test(Request $request)
    {
        $log['data'] = $request->getContent();
        DB::table('finger_logs')->insert($log);
    }

    public function getrequest(Request $request)
    {
        $r = "GET OPTION FROM: ".$this->sn."\nStamp=".strtotime('now')."\nOpStamp=".strtotime('now')."\nErrorDelay=60\nDelay=30\nResLogDay=18250\nResLogDelCount=10000\nResLogCount=50000\nTransTimes=00:00;14:05\nTransInterval=1\nTransFlag=1111000000\nRealtime=1\nEncrypt=0";

        return response()->json($r);
    }

    private function validateAndFormatInteger($value)
    {
        return isset($value) && $value !== '' ? (int) $value : null;
        // return is_numeric($value) ? (int) $value : null;
    }
}
