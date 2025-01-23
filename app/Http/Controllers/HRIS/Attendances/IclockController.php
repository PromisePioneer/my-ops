<?php

namespace App\Http\Controllers\HRIS\Attendances;

use App\Http\Controllers\Controller;
use App\Models\FingerLog;
use App\Service\Attendances\IclockService;
use Illuminate\Http\Request;
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


   public function register(Request $request)
    {
        $sn = $request->query('SN');

        // Validate SN parameter
        if (!$sn) {
            return response("Missing SN parameter", 400)
                ->header('Content-Type', 'text/plain');
        }

        // Check if this command was recently processed for the same SN
        $cacheKey = "attendance_query_{$sn}";

        dd(Cache::has($cacheKey));
        if (Cache::has($cacheKey)) {
            // If cached, return acknowledgment to prevent repeated processing
            return response("OK", 200)
                ->header('Content-Type', 'text/plain');
        }

        // Log received SN for debugging
        Log::info('Received SN: ' . $sn);

        // Set command parameters
        $cmdId = 1;
        $startDate = date("Y-m-d\TH:i:s", strtotime("2024-12-28 06:00:00"));
        $endDate = date("Y-m-d\TH:i:s", strtotime("2025-01-23 23:00:00"));

        // Create the command
        $command = sprintf(
            "C:%d:DATA QUERY ATTLOG StartTime=%s\tEndTime=%s",
            $cmdId,
            $startDate,
            $endDate
        );

        // Log the generated command for debugging
        Log::info('Generated Command: ' . $command);

        // Cache this request to prevent repeated execution for 1 minute
        Cache::put($cacheKey, true, now()->addMinutes(5));

        // Return the command to the machine
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
