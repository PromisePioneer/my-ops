<?php

namespace App\Http\Controllers\HRIS\Attendances;

use App\Http\Controllers\Controller;
use App\Models\FingerLog;
use App\Service\Attendances\IclockService;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
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


    public function getRequest(Request $request): Application|Response|ResponseFactory
    {
        $sn = $request->query('SN');

        // Validate SN parameter
        if (!$sn) {
            return response("Missing SN parameter", 400)
                ->header('Content-Type', 'text/plain');
        }


        Log::info('Received SN: ' . $sn);

        $cmdId = 1;
        $startDate = date("Y-m-d\TH:i:s", strtotime("2024-04-27 00:10:00"));
        $endDate = date("Y-m-d\TH:i:s", strtotime("2024-04-29 03:00:00"));


        $command = sprintf(
            "C:%d:DATA QUERY ATTLOG PIN=%s StartTime=%s\tEndTime=%s",
            $cmdId,
            '5',
            $startDate,
            $endDate
        );


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

}
