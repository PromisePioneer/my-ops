<?php

namespace App\Service\Attendances;

use App\Models\Attendances;
use App\Models\DeviceLog;
use App\Models\EmployeeSchedule;
use App\Models\FingerLog;
use App\Models\FpDevice;
use App\Models\UserWorkTime;
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
                    $shift = $this->getShiftForUser($attendanceData['employee_id'], $attendanceData['timestamp'], $attendanceData['status1']);
//dd($shift);

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

    private function getShiftForUser(string $employeeId, $date, $status1)
    {
        $dateTime = Carbon::parse($date);

        // Attempt to find the shift for the exact date
        $userShift = EmployeeSchedule::with('workTime')
            ->where('employee_id', $employeeId);


        if ($dateTime->toTimeString() >= "01:00:00" && $dateTime->toTimeString() <= "05:00:00" && $status1 === 1) {
            $userShift->whereDate('date', $dateTime->subDay()->format('Y-m-d'));
        } else {
            $userShift->whereDate('date', $dateTime->format('Y-m-d'));
        }


        $userShift = $userShift->first();


        if (!$userShift) {
            // Return default work time if no shift is found
            return WorkTime::find(1);
        }


        return $userShift->workTime ?? WorkTime::find(1); // Fallback if the current time doesn't match the shift
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

    private function processCheckIn(array $attendanceData, $shift, string $date): void
    {
        if ($this->isValidTime($date, $shift->clock_in, $shift->clock_out, $shift->time_to_checkin, $shift->end_time_to_checkin)) {
            Attendances::create($attendanceData);
        }
    }

    private function isValidTime($date, string $startTime, string $endTime, string $checkInStart, string $checkInEnd): bool
    {
        $dateTime = Carbon::parse($date);

        $shiftStart = Carbon::parse($dateTime->format('Y-m-d') . ' ' . $startTime);
        $shiftEnd = Carbon::parse($dateTime->format('Y-m-d') . ' ' . $endTime);


        if ($endTime < $startTime) {
            $shiftEnd->addDay();
        }

        $checkInStartTime = Carbon::parse($dateTime->format('Y-m-d') . ' ' . $checkInStart);
        $checkInEndTime = Carbon::parse($dateTime->format('Y-m-d') . ' ' . $checkInEnd);

        if ($checkInEnd < $checkInStart) {
            $checkInEndTime->addDay();
        }

        if ($dateTime->lessThan($checkInStartTime) && $checkInStart === '23:00:00') {
            $checkInStartTime->subDay();
            $shiftStart->subDay();
        }


        if ($dateTime->greaterThan($checkInStartTime) && $checkInStart === '23:00:00') {
            $shiftStart->addDays();
        }

        if ($checkInStart === "15:00:00" && $dateTime->lessThan($shiftStart)) {
            $shiftStart->subHours();
        }

//        dd($shiftStart);

        // Validate if the time falls within check-in window and shift duration
        return $dateTime->between($checkInStartTime, $checkInEndTime) &&
            $dateTime->between($shiftStart, $shiftEnd);
    }


    private function isValidTimeCheckOut($date, string $startTime, string $endTime, string $checkOutStart, string $checkOutEnd): bool
    {
        $dateTime = Carbon::parse($date);

        $shiftStart = Carbon::parse($dateTime->format('Y-m-d') . ' ' . $startTime);
        $shiftEnd = Carbon::parse($dateTime->format('Y-m-d') . ' ' . $endTime);


        if ($endTime > $startTime) {
            $shiftEnd->addDay();
        }

        $checkOutStartTime = Carbon::parse($dateTime->format('Y-m-d') . ' ' . $checkOutStart);
        $checkOutEndTime = Carbon::parse($dateTime->format('Y-m-d') . ' ' . $checkOutEnd);

        if ($checkOutEnd > $checkOutStart && $checkOutStart === '23:00:00') {
            $checkOutEndTime->addDay();
        }

        if ($dateTime->lessThan($checkOutStartTime) && $checkOutStart === '23:00:00') {
            $checkOutStartTime->subDay();
            $shiftStart->subDay();
        }


        // Validate if the time falls within check-in window and shift duration
        return $dateTime->between($checkOutStartTime, $checkOutEndTime) &&
            $dateTime->between($shiftStart, $shiftEnd);
    }


    private function processCheckOut(array $attendanceData, $shift, string $date, string $time): void
    {
        if ($this->isValidTimeCheckOut($time, $shift->clock_in, $shift->clock_out, $shift->time_to_checkout, $shift->end_time_to_checkout)) {
            Attendances::create($attendanceData);
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
