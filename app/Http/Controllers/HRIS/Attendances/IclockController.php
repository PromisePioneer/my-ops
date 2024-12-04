<?php

namespace App\Http\Controllers\HRIS\Attendances;

use App\Http\Controllers\Controller;
use App\Models\FingerLog;
use App\Service\Attendances\IclockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use JetBrains\PhpStorm\NoReturn;
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


    #[NoReturn] public function register(Request $request): string
    {
        $getContent = $request->getContent();
        Log::info($getContent);

        $rand = mt_rand();
        return "C:$rand:" .
            "ENROLL_FP " .
            "PIN={{UserId}}\t" .
            "FID={{FingerPrintID}}\t" .
            "RETRY={{NumberOfRetry}}\t" .
            "OVERWRITE={{OverwriteExisting}}";
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
