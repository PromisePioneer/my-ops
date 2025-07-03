<?php

namespace App\Support\Attendances\AttendanceSummary;

use App\Models\AttendanceSummary;
use App\Models\BranchDefaultWorkTime;
use App\Models\BranchRoleDefaultWorkTime;
use App\Models\DeviceLog;
use App\Models\EmployeeSchedule;
use App\Models\FingerLog;
use App\Models\FpDevice;
use App\Models\RoleDefaultWorkTime;
use App\Models\User;
use App\Models\WorkTime;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;
use function App\Helper\formatDate;

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
        try {
            $processedCount = 0;
            DB::transaction(function () use ($processedCount, $request) {
                $content['url'] = json_encode($request->all());
                $content['data'] = $request->getContent();
                FingerLog::create($content);


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

                    $this->processAttendanceRecord($attendanceData, $shift);
                    $processedCount++;
                }
                return 'OK: ' . $processedCount;
            });
            return 'OK: ' . $processedCount;
        } catch (Throwable $e) {
            Log::info($e);
            return 'ERROR: ' . $e . "\n";
        }
    }

    public function handleOperLog(array $lines): string
    {
        $count = count(array_filter($lines, fn($line) => !empty(trim($line))));

        return 'OK: ' . $count;
    }

    public function prepareAttendanceData(string $line, Request $request): array
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

    public function validateAndFormatInteger($value): ?int
    {
        return isset($value) && $value !== '' ? (int)$value : null;
    }

    public function getShiftForUser(string $employeeId, $date, $status1)
    {
        $dateTime = Carbon::parse($date);

        $userShift = null;


        if ($dateTime->between(Carbon::parse($dateTime->copy()->format('Y-m-d') . '23:00:00'), Carbon::parse($dateTime->copy()->format('Y-m-d') . '23:59:59'))) {
            $userShift = EmployeeSchedule::with('workTime')
                ->where('employee_id', $employeeId)
                ->whereDate('start_date', $dateTime->format('Y-m-d'))
                ->first();
        }

        if ($dateTime->between(Carbon::parse($dateTime->copy()->format('Y-m-d') . '00:00:00'), Carbon::parse($dateTime->copy()->format('Y-m-d') . '02:00:00'))) {
            $userShift = EmployeeSchedule::with('workTime')
                ->where('employee_id', $employeeId)
                ->whereDate('end_date', $dateTime->format('Y-m-d'))
                ->first();
        }


        if ($dateTime->between(Carbon::parse($dateTime->copy()->format('Y-m-d') . '03:00:00'), Carbon::parse($dateTime->copy()->format('Y-m-d') . '06:00:00'))) {
            $userShift = EmployeeSchedule::with('workTime')
                ->where('employee_id', $employeeId)
                ->whereDate('end_date', $dateTime->format('Y-m-d'))
                ->first();
        }


        if ($dateTime->between(Carbon::parse($dateTime->copy()->format('Y-m-d') . '09:00:00'), Carbon::parse($dateTime->copy()->format('Y-m-d') . '12:00:00'))) {
            $userShift = EmployeeSchedule::with('workTime')
                ->where('employee_id', $employeeId)
                ->whereDate('end_date', $dateTime->format('Y-m-d'))
                ->first();
        }


        if ($dateTime->between(Carbon::parse($dateTime->copy()->format('Y-m-d') . '17:00:00'), Carbon::parse($dateTime->copy()->format('Y-m-d') . '20:00:00'))) {
            $userShift = EmployeeSchedule::with('workTime')
                ->where('employee_id', $employeeId)
                ->whereDate('start_date', $dateTime->format('Y-m-d'))
                ->first();
        }

        if ($dateTime->between(Carbon::parse($dateTime->copy()->format('Y-m-d') . '03:00:00'), Carbon::parse($dateTime->copy()->format('Y-m-d') . '06:00:00'))) {
            $userShift = EmployeeSchedule::with('workTime')
                ->where('employee_id', $employeeId)
                ->whereDate('end_date', $dateTime->format('Y-m-d'))
                ->first();
        }


        if (!$userShift) {
            $userShift = EmployeeSchedule::with('workTime')
                ->where('employee_id', $employeeId)
                ->whereDate('start_date', $dateTime)
                ->first();
        }

        $user = User::with('branch')->where('absent_id', $employeeId)->first();
        if ($user) {
            $branchRoleDefaultWorkTime = BranchRoleDefaultWorkTime::where('branch_id', $user->branch_id)
                ->where('role_id', $user->roles()->first()->id)
                ->first();
            $ifBranchRoleHasWorkTime = WorkTime::find($branchRoleDefaultWorkTime?->work_time_id);
            $branchDefaultWorkTime = BranchDefaultWorkTime::where('branch_id', $user->branch_id)->first()?->work_time_id;
            $ifHasBranchWorkTime = WorkTime::find($branchDefaultWorkTime);
            $roleDefaultWorkTime = RoleDefaultWorkTime::where('role_id', $user->roles()->first()->id)->first()?->work_time_id;
            $ifHasRoleWorkTime = WorkTime::find($roleDefaultWorkTime);
        }
        return $userShift ?? $ifBranchRoleHasWorkTime ?? $ifHasBranchWorkTime ?? $ifHasRoleWorkTime ?? WorkTime::where('is_default', true)->first();
    }


    public function processAttendanceRecord(array $attendanceData, $shift): void
    {
        $date = Carbon::parse($attendanceData['timestamp']);
        $shiftTimeToCheckIn = $shift->start_date ? Carbon::make($shift->start_date . ' ' . $shift->workTime?->time_to_checkin) : null;
        $shiftEndTimeToCheckIn = $shift->start_date ? Carbon::make($shift->start_date . ' ' . $shift?->workTime?->end_time_to_checkin) : null;
        $shiftTimeToCheckOut = $shift->start_date ? Carbon::make($shift->end_date . ' ' . $shift->workTime?->time_to_checkout) : null;
        $shiftEndTimeToCheckOut = $shift->start_date ? Carbon::make($shift->end_date . ' ' . $shift?->workTime?->end_time_to_checkout) : null;

        if ($shift?->workTime?->name === 'Malam') {
            $startDateEmpSchedule = $shift->start_date ? Carbon::make($shift->start_date)->format('Y-m-d') : null;
            $endDateEmpSchedule = $shift->end_date ? Carbon::make($shift->end_date)->format('Y-m-d') : null;
            $shiftTimeToCheckIn = Carbon::parse($startDateEmpSchedule . ' ' . $shift->workTime?->time_to_checkin);
            $shiftEndTimeToCheckIn = Carbon::parse($endDateEmpSchedule . ' ' . $shift->workTime?->end_time_to_checkin);
        }


        $isCheckIn = $this->isValidTimeToCheckIn($date, $shiftTimeToCheckIn ?? $shift->time_to_checkin, $shiftEndTimeToCheckIn ?? $shift->end_time_to_checkin, $shift?->name);
        $isCheckOut = $this->isValidTimeCheckOut($date, $shiftTimeToCheckOut ?? $shift->time_to_checkout, $shiftEndTimeToCheckOut ?? $shift->end_time_to_checkout, $shift?->name, $attendanceData['employee_id']);


        if ($isCheckIn || $isCheckOut) {
            $attendancesSummary = $this->findOrCreateSummary($attendanceData, $shift, $date);
            if (!$attendancesSummary->clock_in && $isCheckIn) {
                $attendancesSummary->clock_in = $date;
                activity()
                    ->causedBy(User::where('absent_id', $attendanceData['employee_id'])->first()->id)
                    ->withProperties([
                        'attributes' => [
                            'employee_id' => $attendanceData['employee_id'],
                            'date' => formatDate($attendanceData['timestamp']),
                            'clock_in' => Carbon::parse($attendanceData['timestamp'])->format('l, j F Y h:i A'),
                            'Shift' => $shift->workTime?->name ?? $shift->name,
                            'Lokasi Absen' => FpDevice::where('serial_number', $attendanceData['sn'])->first()->name,
                        ]
                    ])->log('Clock In');
            }
            if (!$attendancesSummary->clock_out && $isCheckOut) {
                $attendancesSummary->clock_out = $date;
                activity()
                    ->causedBy(User::where('absent_id', $attendanceData['employee_id'])->first()->id)
                    ->withProperties([
                        $attendanceData
                    ])->log('Clock Out');
            }
            $attendancesSummary->save();
        } else {
            setlocale(LC_ALL, 'IND');
            activity()->withProperties([
                'timestamp' => $date,
                'employee_id' => $attendanceData,
                'shift' => $shift,
                'checkin' => $isCheckIn,
                'checkout' => $isCheckOut
            ])->event('Absen')
                ->causedBy(User::where('absent_id', $attendanceData['employee_id'])->first()->id)->withProperties([
                    'attributes' => [
                        'TANGGAL ABSEN' => $date->locale('id')->settings(['formatFunction' => 'translatedFormat'])->format('l, j F Y, h:i a'),
                        'ID ABSEN' => $attendanceData['employee_id'],
                        'BATAS CHECKIN' => $shift->time_to_checkin . ' - ' . $shift->end_time_to_checkin,
                        'BATAS CHECKOUT' => $shift->time_to_checkout . ' - ' . $shift->end_time_to_checkout,
                        'SHIFT SEHARUSNYA' => "$shift->name ({$shift->clock_in} - {$shift->clock_out})",
                        'ABSEN DI' => $attendanceData[''],
                    ]
                ])->log('Absen dilewati karena tidak sesuai dengan jadwal');
        }
    }

    public function findOrCreateSummary(array $attendanceData, $shift, string $date)
    {

        $user = User::with('branch')->where('absent_id', $attendanceData['employee_id'])->first();


        if ($user) {
            $branchRoleDefaultWorkTime = BranchRoleDefaultWorkTime::where('branch_id', $user->branch_id)
                ->where('role_id', $user->roles()->first()->id)
                ->first();
            $ifBranchRoleHasWorkTime = WorkTime::find($branchRoleDefaultWorkTime?->work_time_id)?->id;
            $ifBranchHasUniversalWorkTime = BranchDefaultWorkTime::where('branch_id', $user->branch_id)->first()?->work_time_id;
            $ifHasBranchWorkTime = WorkTime::find($ifBranchHasUniversalWorkTime)?->id;
            $ifRoleHasWorkTime = RoleDefaultWorkTime::where('role_id', $user->roles()->first()->id)->first();
            $ifHasRoleWorkTime = WorkTime::find($ifRoleHasWorkTime?->work_time_id)?->id;
        }
        $shift = $shift->workTime?->id ?? $ifBranchRoleHasWorkTime ?? $ifHasBranchWorkTime ?? $ifHasRoleWorkTime ?? WorkTime::where('is_default', true)->first()?->id;

        $date = Carbon::parse($date);

        $queryDate = $this->getShiftDate($date, $attendanceData['employee_id']);

        $summary = AttendanceSummary::where('employee_id', $attendanceData['employee_id'])->where('work_time_id', $shift)->whereDate('date', $queryDate->format('Y-m-d'))
            ->first();


        if (!$summary) {
            $summary = new AttendanceSummary([
                'date' => $queryDate->format('Y-m-d'),
                'employee_id' => $attendanceData['employee_id'],
                'work_time_id' => $shift,
            ]);
        }

        return $summary;
    }


    private function getShiftDate(Carbon $timestamp, $employeeId): Carbon|null
    {
        $userShift = null;

        if ($timestamp->between(Carbon::parse($timestamp->copy()->format('Y-m-d') . '23:00:00'), Carbon::parse($timestamp->copy()->format('Y-m-d') . '23:59:59'))) {
            $userShift = EmployeeSchedule::with('workTime')
                ->where('employee_id', $employeeId)
                ->whereDate('start_date', $timestamp->format('Y-m-d'))
                ->first()?->start_date;
        }

        if ($timestamp->between(Carbon::parse($timestamp->copy()->format('Y-m-d') . '00:00:00'), Carbon::parse($timestamp->copy()->format('Y-m-d') . '02:00:00'))) {
            $userShift = EmployeeSchedule::with('workTime')
                ->where('employee_id', $employeeId)
                ->whereDate('end_date', $timestamp->format('Y-m-d'))
                ->first()?->start_date;
        }


        if ($timestamp->between(Carbon::parse($timestamp->copy()->format('Y-m-d') . '09:00:00'), Carbon::parse($timestamp->copy()->format('Y-m-d') . '12:00:00'))) {
            $userShift = EmployeeSchedule::with('workTime')
                ->where('employee_id', $employeeId)
                ->whereDate('end_date', $timestamp->format('Y-m-d'))
                ->first()?->start_date;
        }


        if ($timestamp->between(Carbon::parse($timestamp->copy()->format('Y-m-d') . '03:00:00'), Carbon::parse($timestamp->copy()->format('Y-m-d') . '06:00:00'))) {
            $userShift = EmployeeSchedule::with('workTime')
                ->where('employee_id', $employeeId)
                ->whereDate('end_date', $timestamp->format('Y-m-d'))
                ->first()?->start_date;
        }


        if ($timestamp->between(Carbon::parse($timestamp->copy()->format('Y-m-d') . '17:00:00'), Carbon::parse($timestamp->copy()->format('Y-m-d') . '20:00:00'))) {
            $userShift = EmployeeSchedule::with('workTime')
                ->where('employee_id', $employeeId)
                ->whereDate('start_date', $timestamp->format('Y-m-d'))
                ->first()?->start_date;
        }


        if (!$userShift) {
            $userShift = EmployeeSchedule::with('workTime')
                ->where('employee_id', $employeeId)
                ->whereDate('start_date', $timestamp->format('Y-m-d'))
                ->first()?->start_date;
        }

        return Carbon::parse($userShift ?? $timestamp);
    }

    public function isValidTimeToCheckIn($date, string $checkInStart, string $checkInEnd, $shiftName): bool
    {
        if ($shiftName === 'Pagi' || $shiftName === 'Lapangan' || $shiftName === 'Duri') {
            $actualCheckInTime = Carbon::parse($date)->toTimeString();
            return $actualCheckInTime >= $checkInStart && $actualCheckInTime <= $checkInEnd;
        }
        $date = Carbon::parse($date);
        $checkInStart = Carbon::make($checkInStart);
        $checkInEnd = Carbon::make($checkInEnd);


        return $date->greaterThanOrEqualTo($checkInStart) && $date->lessThanOrEqualTo($checkInEnd);
    }


    public function isValidTimeCheckOut($date, string $checkOutStart, string $checkOutEnd, $shiftName, $employee_id): bool
    {
        if ($shiftName === 'Pagi' || $shiftName === 'Lapangan' || $shiftName === 'Duri') {
            $actualCheckOutTime = Carbon::parse($date)->toTimeString();

            return $actualCheckOutTime >= $checkOutStart && $actualCheckOutTime <= $checkOutEnd;
        }


        $date = Carbon::parse($date);
        $checkOutStart = Carbon::parse($checkOutStart);
        $checkOutEnd = Carbon::parse($checkOutEnd);


        return $date->greaterThanOrEqualTo($checkOutStart) && $date->lessThanOrEqualTo($checkOutEnd);
    }
}
