<?php

namespace App\Http\Controllers\HRIS\Attendances;

use AllowDynamicProperties;
use App\Http\Controllers\Controller;
use App\Models\FingerLog;
use App\Support\Attendances\IclockService;
use Illuminate\Http\Request;
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
    }


    public function getRequest(Request $request): string
    {
        return "OK";
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
