<?php

namespace App\Http\Controllers\HRIS\Attendances;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserRequest;
use App\Models\FingerLog;
use App\Service\Attendances\IclockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class IclockController extends Controller
{
    private IclockService $iclockService;

    /**
     * @throws Throwable
     */
    public function __construct()
    {
        $this->iclockService = new IclockService();
    }


   public function register(Request $request)
    {
        // Ambil serial number (SN) dari mesin
        // $sn = $request->query('SN');


        //     // Logika untuk menentukan perintah berdasarkan SN atau kondisi lainnya
        //     $command = sprintf(
        //     "C:%d:ENROLL_FP PIN=%d\tFID=%d\tRETRY=%d\tOVERWRITE=%d",
        //     1, // CmdId
        //     12345, // UserId
        //     1, // Fingerprint ID
        //     0, // Retry count
        //     1  // Overwrite existing
        // );

        //     return response($command, 200)
        //         ->header('Content-Type', 'text/plain');

    }

        public function handshake(Request $request): string
    {
        return $this->iclockService->handshake($request);
    }

    public function receiveRecords(Request $request): string
    {
        return $this->iclockService->recieveRecords($request);
    }

    public function test(Request $request): void
    {
        $log['data'] = $request->getContent();
        FingerLog::create($log);
    }

    public function getrequest(Request $request): string
    {
        $content['url'] = json_encode($request->all());
        $cmdId = 1;
        return "C:{}:ENROLL_FP<spasi>PIN={{UserId}}<tab>FID={{FingerPrintID}}<tab>RETRY={{NumberOfRetry}}<tab>OVERWRITE={{OverwriteExisting}}";
    }
}
