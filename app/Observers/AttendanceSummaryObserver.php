<?php

namespace App\Observers;

use App\Models\Attendances;
use App\Models\AttendancesSummary;
use App\Models\EmployeeSchedule;
use App\Models\User;
use App\Models\WorkTime;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AttendanceSummaryObserver
{
    public function created(Attendances $attendances): void
    {
        $timestamp = Carbon::parse($attendances->timestamp);

        $workTime = null;
        $user = User::where('absent_id', $attendances->employee_id)->first();

        if ($attendances->status1 === 0) {
            $workTime = $user->hasRole('Engineer') ? WorkTime::find(2) : WorkTime::whereTime('time_to_checkin', '<=', $timestamp->toTimeString())
                ->whereTime('end_time_to_checkin', '>=', $timestamp->toTimeString())
                ->first() ?? EmployeeSchedule::with('workTime')
                ->where('employee_id', $attendances->employee_id)
                ->whereDate('date', $timestamp)
                ->first() ?? WorkTime::find(1);





        } elseif ($attendances->status1 === 1) {
            $workTime = $user->hasRole('Engineer') ? WorkTime::find(2) : WorkTime::whereTime('time_to_checkout', '<=', $timestamp->toTimeString())
            ->whereTime('end_time_to_checkout', '>=', $timestamp->toTimeString())
            ->first() ?? EmployeeSchedule::with('workTime')
            ->where('employee_id', $attendances->employee_id)
            ->whereDate('date', $timestamp)
                ->first() ?? WorkTime::find(1);
        }


        if ($workTime?->status === 'L') {
            return;
        }

        if (!$workTime) {
            Log::warning('No matching WorkTime found for timestamp: ' . $timestamp);
            return;
        }

        $expectedCheckIn = Carbon::make($timestamp->format('Y-m-d') . $workTime->workTime?->clock_in);


        $attendancesSummary = AttendancesSummary::where('employee_id', $attendances->employee_id)
            ->where('work_time_id', $workTime?->workTime?->id ?? $workTime->id);

        if ($workTime?->workTime?->clock_in !== "00:00:00" && $workTime->clock_out !== "01:00:00") {
            $attendancesSummary->whereDate('date', $timestamp);
        }

        if ($workTime?->workTime?->clock_in === "00:00:00") {
            if($expectedCheckIn->lessThan($timestamp)){
                $attendancesSummary->whereDate('date', $timestamp);
            }else{
                $attendancesSummary->whereDate('date', $timestamp->subDay());
            }
        }

        if ($workTime->clock_out === "01:00:00") {
            $attendancesSummary->whereDate('date', $timestamp->copy()->subDay());
        }

//        dd($timestamp->copy()->subDay()->format('Y-m-d'));


        $attendancesSummary = $attendancesSummary->first();


        Log::info($attendancesSummary);


        if (!$attendancesSummary) {
            $attendancesSummary = new AttendancesSummary([
                'date' => $timestamp->format('Y-m-d'),
                'employee_id' => $attendances->employee_id,
                'work_time_id' => $workTime?->workTime?->id ?? $workTime->id,
            ]);
        }

        $expectedCheckOut = Carbon::make($timestamp->format('Y-m-d') . $workTime->workTime?->clock_out);

        if ($attendances->status1 === 0 && !$attendancesSummary->clock_in) {
            $clockInTimestamp = $timestamp->copy()->subDays();

            // Log::info($expectedCheckIn->lessThan($timestamp));

            if ($workTime?->workTime?->clock_in === "00:00:00" && $expectedCheckIn->greaterThan($timestamp)) {
                $attendancesSummary->clock_in = $clockInTimestamp;
            } else {
                $attendancesSummary->clock_in = $timestamp;
            }

        } elseif ($attendances->status1 === 1 && !$attendancesSummary->clock_out) {
            $clockOutTimestamp = $timestamp->copy()->addDays();
            if ($workTime?->workTime?->clock_out === "00:00:00" && $expectedCheckOut->greaterThan($timestamp)) {
                $attendancesSummary->clock_out = $clockOutTimestamp;
            } else {
                $attendancesSummary->clock_out = $timestamp;
            }
        }

        $attendancesSummary->save();
    }
}
