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

        $startDate = Carbon::make($request->header('startdate'));
       $endDate = Carbon::make($request->header('enddate'));

       $command = sprintf(
           "C:%s:DATA QUERY ATTLOG StartTime%s\tEndTime=%s",
           $cmdId,
           $startDate,
           $endDate);

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
        //
    }
}
