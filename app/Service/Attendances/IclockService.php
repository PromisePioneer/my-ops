<?php

namespace App\Service\Attendances;

use App\Models\Attendances;
use App\Models\DeviceLog;
use App\Models\EmployeeSchedule;
use App\Models\FingerLog;
use App\Models\FpDevice;
use App\Models\WorkTime;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class IclockService
{
    public function handshake(Request $request): string
    {
        $data = [
            'url' => json_encode($request->all()),
            'data' => $request->getContent(),
            'serial_number' => $request->input('SN'),
            'option' => $request->input('option'),
        ];
        DeviceLog::create($data);

        FpDevice::updateOrCreate(
            ['serial_number' => $request->input('SN')],
            [
                'online' => now(),
            ]
        );

        return "GET OPTION FROM: {$request->input('SN')}\r\n" .
            "Stamp=9999\r\n" .
            'OpStamp=' . time() . "\r\n" .
            "ErrorDelay=60\r\n" .
            "Delay=30\r\n" .
            "ResLogDay=18250\r\n" .
            "ResLogDelCount=10000\r\n" .
            "ResLogCount=50000\r\n" .
            "TransTimes=00:00;14:05\r\n" .
            "TransInterval=1\r\n" .
            "TransFlag=1111000000\r\n" .
            "TimeZone=7\r\n" .
            "Realtime=1\r\n" .
            'Encrypt=0';
    }

    public function recieveRecords(Request $request): string
    {
        $content['url'] = json_encode($request->all());
        $content['data'] = $request->getContent();
        FingerLog::create($content);


        $processedCount = 0;
        try {
            DB::transaction(function () use ($processedCount, $request) {
                $inputLines = preg_split('/\r\n|\r|\n/', $request->getContent());

                if ($request->input('table') == 'OPERLOG') {
                    return $this->handleOperLog($inputLines);
                }

                foreach ($inputLines as $line) {
                    if (empty(trim($line))) {
                        continue;
                    }
                    $attendanceData = $this->prepareAttendanceData($line, $request);
                    $shift = $this->getShiftForUser($attendanceData['employee_id'], $attendanceData['timestamp']);
                    $this->processAttendanceRecord($attendanceData, $shift);
                    $processedCount++;
                }

                return 'OK: ' . $processedCount;
            });

            return 'OK: ' . $processedCount;
        } catch (Throwable $e) {
            // Log and report any errors
            Log::info($e);

            return 'ERROR: ' . $e . "\n";
        }
    }

    private function handleOperLog(array $lines): string
    {
        $count = count(array_filter($lines, fn($line) => !empty(trim($line))));

        return 'OK: ' . $count;
    }

    private function prepareAttendanceData(string $line, Request $request): array
    {
        // Split line by tab character
        $data = explode("\t", $line);


        return [
            'sn' => $request->input('SN'),
            'table' => $request->input('table'),
            'stamp' => $request->input('Stamp'),
            'employee_id' => $data[0],
            'timestamp' => $data[1],
            'status1' => $this->validateAndFormatInteger($data[2] ?? null),
        ];
    }

    private function validateAndFormatInteger($value): ?int
    {
        return isset($value) && $value !== '' ? (int)$value : null;
    }

    private function getShiftForUser(string $employeeId, $date)
    {
        $userShift = EmployeeSchedule::with('workTime')
            ->where('employee_id', $employeeId)
            ->whereDate('date', Carbon::parse($date))
            ->orWhereDate('date', Carbon::parse($date)->copy()->subDays())
            ->first();

        return $userShift
            ? WorkTime::find($userShift->workTime->id) ?? WorkTime::find(1)
            : WorkTime::find(1);
    }

    private function processAttendanceRecord(array $attendanceData, $shift): void
    {
        $date = Carbon::parse($attendanceData['timestamp']);


        if ($attendanceData['status1'] == 0) {
            $this->processCheckIn($attendanceData, $shift, $date, $date);
        } elseif ($attendanceData['status1'] == 1) {
            $this->processCheckOut($attendanceData, $shift, $date, $date);
        }

    }

    private function processCheckIn(array $attendanceData, $shift, string $date, string $time): void
    {
        if ($this->isValidTime($time, $shift->time_to_checkin, $shift->end_time_to_checkin, $shift->name)) {
            $existingRecord = Attendances::where('employee_id', $attendanceData['employee_id'])
                ->whereDate('timestamp', Carbon::parse($date))
                ->where('status1', 0)
                ->exists();
            if (!$existingRecord) {
                Attendances::create($attendanceData);
            }
        }
    }

    private function isValidTime($date, string $startTime, string $endTime, $name): bool
    {
        $dateTime = Carbon::parse($date);
        $times = $dateTime;

        $startTimes = Carbon::parse($dateTime->format('Y-m-d') . ' ' . $startTime);
        $endTimes = Carbon::parse($dateTime->format('Y-m-d') . ' ' . $endTime);

        $newStartTimesForMidnightShift = null;
        $newEndtimesForMidnightShift = null;
        if ($name == 'Malam') {
            $newStartTimesForMidnightShift = $startTimes->copy();
            $newEndtimesForMidnightShift = $endTimes->copy();

            if ($times->lessThan($newStartTimesForMidnightShift)) {
                $newStartTimesForMidnightShift->subDays();
            }

            if ($times->greaterThan($newEndtimesForMidnightShift)) {
                $newEndtimesForMidnightShift->addDays();
            }
        }

        return $times->greaterThanOrEqualTo($newStartTimesForMidnightShift ?? $startTimes) && $times->lessThanOrEqualTo($newEndTimesForMidnightShift ?? $endTimes);
    }

    private function processCheckOut(array $attendanceData, $shift, string $date, string $time): void
    {
        if ($this->isValidTime($time, $shift->time_to_checkout, $shift->end_time_to_checkout, $shift->name)) {
        $existingCheckOut = Attendances::where('employee_id', $attendanceData['employee_id'])
            ->whereDate('timestamp', Carbon::parse($date))
            ->where('status1', 1)
            ->exists();

            if (!$existingCheckOut) {
                Attendances::create($attendanceData);
            }
        }
    }

    private function logError(Exception $exception): void
    {
        DB::table('error_logs')->insert([
            'data' => $exception->getMessage(),
        ]);
        report($exception);
    }

    private function isValidUserShift(string $employeeId): bool
    {
        return UserWorkTime::whereHas('user', function ($query) use ($employeeId) {
            $query->where('absent_id', $employeeId);
        })->exists();
    }
}
