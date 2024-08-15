<?php

namespace App\Http\Controllers\ADMS;

use App\Http\Controllers\Controller;
use App\Models\Attendances;
use App\Models\DeviceLog;
use App\Models\ErrorLog;
use App\Models\FingerLog;
use App\Models\FpDevice;
use Illuminate\Http\Request;
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
    public function receiveRecords(Request $request): string
    {
        //DB::connection()->enableQueryLog();
        $content['url'] = json_encode($request->all());
        $content['data'] = $request->getContent();
        FingerLog::create($content);
        try {
            // $post_content = $request->getContent();
            //$arr = explode("\n", $post_content);
            $arr = preg_split('/\\r\\n|\\r|,|\\n/', $request->getContent());
            //$tot = count($arr);
            $tot = 0;
            //operation log
            if ($request->input('table') == "OPERLOG") {
                // $tot = count($arr) - 1;
                foreach ($arr as $rey) {
                    if (isset($rey)) {
                        $tot++;
                    }
                }
                return "OK: ".$tot;
            }
            //attendance
            foreach ($arr as $rey) {
                // $data = preg_split('/\s+/', trim($rey));
                if (empty($rey)) {
                    continue;
                }
                // $data = preg_split('/\s+/', trim($rey));
                $data = explode("\t", $rey);
                $q['sn'] = $request->input('SN');
                $q['table'] = $request->input('table');
                $q['stamp'] = $request->input('Stamp');
                $q['employee_id'] = $data[0];
                $q['timestamp'] = $data[1];
                $q['status1'] = $this->validateAndFormatInteger($data[2] ?? null);
                $q['status2'] = $this->validateAndFormatInteger($data[3] ?? null);
                $q['status3'] = $this->validateAndFormatInteger($data[4] ?? null);
                $q['status4'] = $this->validateAndFormatInteger($data[5] ?? null);
                $q['status5'] = $this->validateAndFormatInteger($data[6] ?? null);
                Attendances::create($q);
                $tot++;
                // dd(DB::getQueryLog());
            }
            return "OK: ".$tot;
        } catch (Throwable $e) {
            $data['error'] = $e;
            ErrorLog::create($data);
            report($e);
            return "ERROR: ".$tot."\n";
        }
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

        // Ambil nomor seri dari request
        $sn = $request->query('SN');

        // Contoh data untuk command
        $cmdId = '12345';
        $startTime = '2024-08-15 08:00:00';
        $endTime = '2024-08-15 17:00:00';

        // Siapkan command dengan data dinamis
        $command = "C:{$cmdId}:DATA QUERY ATTLOG StartTime={$startTime}\tEndTime={$endTime}";

        // Simpan perintah dalam array statis
        $commands = [
            'AEWD233960062' => $command, // Assign the dynamic command to a specific SN
        ];

        // Cek apakah ada perintah untuk nomor seri ini
        if (isset($commands[$sn])) {
            $command = $commands[$sn];

            // Hapus perintah setelah dikirim jika diperlukan
            unset($commands[$sn]);

            // Kirimkan perintah ke mesin
            return response($command)
                ->header('Content-Type', 'text/plain');
        }

        // Jika tidak ada perintah, kirim "OK"
        return response('OK')
            ->header('Content-Type', 'text/plain');
    }


    public function getAttLog(Request $request): array
    {
        $rawContent = $request->getContent();
        dd($rawContent);
    }
}
