<?php

namespace App\Service;

use App\Models\Attendances;
use App\Models\DeviceLog;
use App\Models\FingerLog;
use App\Models\FpDevice;
use App\Models\ManageShift;
use App\Models\UserShift;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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


        // update status device
        FpDevice::updateOrCreate(
            ['serial_number' => $request->input('SN')],
            [
                'name' => $request->input('SN'),
                'online' => now()
            ]
        );

        return "GET OPTION FROM: {$request->input('SN')}\r\n".
            "Stamp=9999\r\n".
            "OpStamp=".time()."\r\n".
            "ErrorDelay=60\r\n".
            "Delay=30\r\n".
            "ResLogDay=18250\r\n".
            "ResLogDelCount=10000\r\n".
            "ResLogCount=50000\r\n".
            "TransTimes=00:00;14:05\r\n".
            "TransInterval=1\r\n".
            "TransFlag=1111000000\r\n".
            //  "TimeZone=7\r\n" .
            "Realtime=1\r\n".
            "Encrypt=0";
    }

    public function recieveRecords(Request $request): string
    {
//        try {
//            // $post_content = $request->getContent();
//            //$arr = explode("\n", $post_content);
//            $arr = preg_split('/\\r\\n|\\r|,|\\n/', $request->getContent());
//            //$tot = count($arr);
//            $tot = 0;
//            //operation log
//            if ($request->input('table') == "OPERLOG") {
//                // $tot = count($arr) - 1;
//                foreach ($arr as $rey) {
//                    if (isset($rey)) {
//                        $tot++;
//                    }
//                }
//                return "OK: ".$tot;
//            }
//            //attendance
//            foreach ($arr as $rey) {
//                // $data = preg_split('/\s+/', trim($rey));
//                if (empty($rey)) {
//                    continue;
//                }
//                // $data = preg_split('/\s+/', trim($rey));
//                $data = explode("\t", $rey);
////                dd($data);
//                //dd($data);
//                $q['sn'] = $request->input('SN');
//                $q['table'] = $request->input('table');
//                $q['stamp'] = $request->input('Stamp');
//                $q['employee_id'] = $data[0];
//                $q['timestamp'] = $data[1];
//                $q['status1'] = $this->validateAndFormatInteger($data[2] ?? null);
//                $q['status2'] = $this->validateAndFormatInteger($data[3] ?? null);
//                $q['status3'] = $this->validateAndFormatInteger($data[4] ?? null);
//                $q['status4'] = $this->validateAndFormatInteger($data[5] ?? null);
//                $q['status5'] = $this->validateAndFormatInteger($data[6] ?? null);
//                $q['created_at'] = now();
//                $q['updated_at'] = now();
//                //dd($q);
//                Attendances::create($q);
//                $tot++;
//                // dd(DB::getQueryLog());
//            }
//            return "OK: ".$tot;
//        } catch (Throwable $e) {
//            $data['error'] = $e;
//            DB::table('error_logs')->insert($data);
//            report($e);
//            return "ERROR: ".$tot."\n";
//        }


//        // Log incoming request data
        $content['url'] = json_encode($request->all());
        $content['data'] = $request->getContent();
        FingerLog::create($content);

        $processedCount = 0;
        try {
            DB::transaction(function () use ($processedCount, $request) {
                // Split input lines by various line breaks
                $inputLines = preg_split('/\r\n|\r|\n/', $request->getContent());

                // Handle OPERLOG case separately
                if ($request->input('table') == "OPERLOG") {
                    return $this->handleOperLog($inputLines);
                }

                // Process each line for attendance records
                foreach ($inputLines as $line) {
                    // Skip empty lines
                    if (empty(trim($line))) {
                        continue;
                    }

                    // Prepare attendance data
                    $attendanceData = $this->prepareAttendanceData($line, $request);

//                    dd($this->isValidUserShift($attendanceData['employee_id']));
//                    // Check if user shift is valid
//                    if (!$this->isValidUserShift($attendanceData['employee_id'])) {
//                        continue;
//                    }

                    // Get shift information for the user
                    $shift = $this->getShiftForUser($attendanceData['employee_id']);
//                    dd(!$shift);
//                    if (!$shift) {
//                        continue;
//                    }

                    // Process the attendance record
                    $this->processAttendanceRecord($attendanceData, $shift);
                    $processedCount++;
                }
                return "OK: ".$processedCount;
            });


            return "OK: ".$processedCount;
        } catch (Throwable $e) {
            // Log and report any errors
            $this->logError($e);
            return "ERROR: ".$processedCount."\n";
        }
    }

    private function handleOperLog(array $lines): string
    {
        // Filter out empty lines and count valid ones
        $count = count(array_filter($lines, fn($line) => !empty(trim($line))));
        return "OK: ".$count;
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
        return isset($value) && $value !== '' ? (int) $value : null;
    }

    private function getShiftForUser(string $employeeId)
    {
        $userShift = UserShift::whereHas('user', function ($query) use ($employeeId) {
            $query->where('absent_id', $employeeId);
        })->first();

        return $userShift
            ? ManageShift::find($userShift->shift_id) ?? ManageShift::find(1)
            : ManageShift::find(1);
    }

    private function processAttendanceRecord(array $attendanceData, $shift): void
    {
        $date = date('Y-m-d', strtotime($attendanceData['timestamp']));
        $time = date('H:i:s', strtotime($attendanceData['timestamp']));


        if ($attendanceData['status1'] == 0) {
            $this->processCheckIn($attendanceData, $shift, $date, $time);
        } elseif ($attendanceData['status1'] == 1) {
            $this->processCheckOut($attendanceData, $shift, $date, $time);
        }
    }

    private function processCheckIn(array $attendanceData, $shift, string $date, string $time): void
    {
        if ($this->isValidTime($time, $shift->time_to_checkin, $shift->end_time_to_checkin)) {
            $existingRecord = $this->getAttendanceRecord($attendanceData['employee_id'], $date);

            if (!$existingRecord) {
                dd('nice');
                Attendances::create($attendanceData);
            }
        }
    }

    private function isValidTime(string $time, string $startTime, string $endTime): bool
    {
        return $time >= $startTime && $time <= $endTime;
    }

    private function getAttendanceRecord(string $employeeId, string $date, string $order = 'asc')
    {
        return Attendances::where('employee_id', $employeeId)
            ->whereDate('timestamp', $date)
            ->orderBy('timestamp', $order)
            ->first();
    }

    private function processCheckOut(array $attendanceData, $shift, string $date, string $time): void
    {
        if ($this->isValidTime($time, $shift->time_to_checkout, $shift->end_time_to_checkout)) {
            $existingCheckOut = $this->getAttendanceRecord($attendanceData['employee_id'], $date, 'desc');

            if (!$existingCheckOut || $existingCheckOut->status1 != 1) {
                Attendances::create([
                    'sn' => $attendanceData['sn'],
                    'table' => $attendanceData['table'],
                    'stamp' => $attendanceData['stamp'],
                    'employee_id' => $attendanceData['employee_id'],
                    'timestamp' => $attendanceData['timestamp'],
                    'status1' => 1,
                ]);
            }
        }
    }

    private function logError(Exception $exception): void
    {
        $data['error'] = $exception->getMessage();
        DB::table('error_logs')->insert($data);
        report($exception);
    }

    private function isValidUserShift(string $employeeId): bool
    {
        return UserShift::whereHas('user', function ($query) use ($employeeId) {
            $query->where('absent_id', $employeeId);
        })->exists();
    }

}

