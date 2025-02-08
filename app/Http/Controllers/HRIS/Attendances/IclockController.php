<?php

namespace App\Http\Controllers\HRIS\Attendances;

use AllowDynamicProperties;
use App\Http\Controllers\Controller;
use App\Models\FingerLog;
use App\Service\Attendances\IclockService;
use App\Service\FpDeviceCommandService;
use Illuminate\Http\Request;
use Jmrashed\Zkteco\Lib\ZKTeco;
use MehediJaman\LaravelZkteco\LaravelZkteco;
use Throwable;

#[AllowDynamicProperties] class IclockController extends Controller
{
    private IclockService $iclockService;

    /**
     * @throws Throwable
     */
    public function __construct()
    {
        $this->iclockService = new IclockService();
        $this->FpDeviceCommandService = new FpDeviceCommandService();
    }


    public function getAttendanceViaPushSDK()
    {
        $zk = new LaravelZkteco('103.102.248.163', '4370');
        $zk->connect();
        dd($zk->getAttendance());
        $zk->disconnect();
    }

    public function getRequest(Request $request): string
    {
        return $this->FpDeviceCommandService->queryAttendanceLog();
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
