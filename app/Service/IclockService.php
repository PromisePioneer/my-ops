<?php

namespace App\Service;

use App\Models\Attendances;
use App\Models\DeviceLog;
use App\Models\ErrorLog;
use App\Models\FingerLog;
use App\Models\FpDevice;
use App\Models\ManageShift;
use App\Models\UserShift;
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
        $content['url'] = json_encode($request->all());
        $content['data'] = $request->getContent();
        FingerLog::create($content);
        try {
            // $post_content = $request->getContent();
            //$arr = explode("\n", $post_content);
            $arr = preg_split('/\\r\\n|\\r|,|\\n/', $request->getContent());
            //$tot = count($arr);
            $tot = 0;
            //operation log
            if ($request->input('table') == "OPERLOG") {
                // $tot = count($arr) - 1;
                foreach ($arr as $rey) {
                    if (isset($rey)) {
                        $tot++;
                    }
                }
                return "OK: ".$tot;
            }
            //attendance
            foreach ($arr as $rey) {
                // $data = preg_split('/\s+/', trim($rey));
                if (empty($rey)) {
                    continue;
                }
                // $data = preg_split('/\s+/', trim($rey));
                $data = explode("\t", $rey);
                //dd($data);
                $q['sn'] = $request->input('SN');
                $q['table'] = $request->input('table');
                $q['stamp'] = $request->input('Stamp');
                $q['employee_id'] = $data[0];
                $q['timestamp'] = $data[1];
                $q['status1'] = $this->validateAndFormatInteger($data[2] ?? null);
                $q['status2'] = $this->validateAndFormatInteger($data[3] ?? null);
                $q['status3'] = $this->validateAndFormatInteger($data[4] ?? null);
                $q['status4'] = $this->validateAndFormatInteger($data[5] ?? null);
                $q['status5'] = $this->validateAndFormatInteger($data[6] ?? null);
                $q['created_at'] = now();
                $q['updated_at'] = now();
                //dd($q);
                Attendances::create($q);
                $tot++;
                // dd(DB::getQueryLog());
            }
            return "OK: ".$tot;
        } catch (Throwable $e) {
            $data['error'] = $e;
            DB::table('error_logs')->insert($data);
            report($e);
            return "ERROR: ".$tot."\n";
        }

//        try {
//            $inputLines = preg_split('/\\r\\n|\\r|,|\\n/', $request->getContent());
//            $processedCount = 0;
//
//            if ($request->input('table') == "OPERLOG") {
//                return $this->handleOperLog($inputLines);
//            }
//
//            foreach ($inputLines as $line) {
//                dd($line);
////                if (empty($line)) {
////                    continue;
////                }
//
//                $attendanceData = $this->prepareAttendanceData($line, $request);
//
//                if (!$this->isValidUserShift($attendanceData['employee_id'])) {
//                    continue;
//                }
//
//                $shift = $this->getShiftForUser($attendanceData['employee_id']);
//                if (!$shift) {
//                    continue;
//                }
//
//                $this->processAttendanceRecord($attendanceData, $shift);
//                $processedCount++;
//            }
//
//            return "OK: ".$processedCount;
//        } catch (Throwable $e) {
//            $this->logError($e);
//            return "ERROR: ".$processedCount."\n";
//        }
    }

    private function validateAndFormatInteger($value): ?int
    {
        return isset($value) && $value !== '' ? (int) $value : null;
    }

    private function handleOperLog($lines): string
    {
        $count = count(array_filter($lines));
        return "OK: ".$count;
    }

    private function prepareAttendanceData($line, $request): array
    {
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

    private function isValidUserShift($employeeId)
    {
        return UserShift::whereHas('user', function ($query) use ($employeeId) {
            $query->where('absent_id', $employeeId);
        })->exists();
    }

    private function getShiftForUser($employeeId)
    {
        $userShift = UserShift::whereHas('user', function ($query) use ($employeeId) {
            $query->where('absent_id', $employeeId);
        })->first();

        if (!$userShift) {
            return ManageShift::find(1);
        }

        return ManageShift::find($userShift->shift_id) ?? ManageShift::find(1);
    }

    private function processAttendanceRecord($attendanceData, $shift): void
    {
        $date = date('Y-m-d', strtotime($attendanceData['timestamp']));
        $time = date('H:i:s', strtotime($attendanceData['timestamp']));

        if ($attendanceData['status1'] == 0) {
            $this->processCheckIn($attendanceData, $shift, $date, $time);
        } elseif ($attendanceData['status1'] == 1) {
            $this->processCheckOut($attendanceData, $shift, $date, $time);
        }
    }

    private function processCheckIn($attendanceData, $shift, $date, $time): void
    {
        if ($this->isValidTime($time, $shift->time_to_checkin, $shift->end_time_to_checkin)) {
            $existingRecord = $this->getAttendanceRecord($attendanceData['employee_id'], $date);

            if (!$existingRecord) {
                Attendances::create($attendanceData);
            }
        }
    }

    private function isValidTime(string $time, string $startTime, string $endTime): bool
    {
        return $time >= $startTime && $time <= $endTime;
    }

    private function getAttendanceRecord($employeeId, $date, $order = 'asc')
    {
        return Attendances::where('employee_id', $employeeId)
            ->whereDate('timestamp', $date)
            ->orderBy('timestamp', $order)
            ->first();
    }

    private function processCheckOut($attendanceData, $shift, $date, $time): void
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

    private function logError(Throwable $exception): void
    {
        $data['error'] = $exception->getMessage();
        ErrorLog::create($data);
        report($exception);
    }

}