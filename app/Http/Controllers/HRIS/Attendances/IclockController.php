<?php

namespace App\Http\Controllers\HRIS\Attendances;

use App\Http\Controllers\Controller;
use App\Models\FingerLog;
use App\Service\Attendances\IclockService;
use Carbon\Carbon;
use Illuminate\Http\Request;
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
        $sn = $request->query('SN');

        if (!$sn) {
            return response("Missing SN parameter", 400);
        }

        $cmdId = 1;
        $userId = 1188388;
        // Logika untuk menentukan perintah berdasarkan SN atau kondisi lainnya
//        $command = sprintf(
//            "C:%d:ENROLL_FP PIN=%d\tFID=%d\tRETRY=%d\tOVERWRITE=%d",
//            $cmdId, // CmdId
//            $userId, // UserId
//            1, // Fingerprint ID
//            2, // Retry count
//            0 // Overwrite existing
//        );


       $startDate = Carbon::parse('2024-12-16 00:00:00')->timestamp;
       $endDate = Carbon::parse('2024-12-17 00:00:00')->timestamp;


       $command = sprintf(
           "C:%d:DATA QUERY ATTLOG StartTime=%d\tEndTime=%d",
           $cmdId,
           $startDate,
           $endDate);


        // Respons ke mesin
        return response($command, 200)
            ->header('Content-Type', 'text/plain');
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
