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
            $workTime = $user->hasRole('Engineer') ? WorkTime::find(2) : EmployeeSchedule::with('workTime')
                ->where('employee_id', $attendances->employee_id)
                ->whereDate('date', $timestamp)
                ->first() ?? WorkTime::find(1);
        } elseif ($attendances->status1 === 1) {
            if ($timestamp->toTimeString() >= "9:00:00" && "12:00:00" <= $timestamp->toTimeString()) {
                $workTime = $user->hasRole('Engineer') ? WorkTime::find(2) : EmployeeSchedule::with('workTime')
                    ->where('employee_id', $attendances->employee_id)
                    ->whereDate('date', $timestamp->copy()->subDay())
                    ->first() ?? WorkTime::find(1);
            } elseif ($timestamp->toTimeString() >= "01:00:00" && $timestamp->toTimeString() <= "05:00:00") {
                $workTime = $user->hasRole('Engineer') ? WorkTime::find(2) : EmployeeSchedule::with('workTime')
                    ->where('employee_id', $attendances->employee_id)
                    ->whereDate('date', $timestamp->copy()->subDay())
                    ->first() ?? WorkTime::find(1);
            } else {
                $workTime = $user->hasRole('Engineer') ? WorkTime::find(2) : EmployeeSchedule::with('workTime')
                    ->where('employee_id', $attendances->employee_id)
                    ->whereDate('date', $timestamp)
                    ->first() ?? WorkTime::find(1);
            }
        }

        if (!$workTime) {
            Log::warning('No matching WorkTime found for timestamp: ' . $timestamp);
            return;
        }

        $attendancesSummary = AttendancesSummary::where('employee_id', $attendances->employee_id)
            ->where('work_time_id', $workTime?->workTime?->id ?? $workTime->id);


        if ($workTime?->workTime?->name === "Malam" || $workTime?->workTime?->name === "Sore") {
            $attendancesSummary->whereDate('date', $timestamp->copy()->subDays());
        } else {
            $attendancesSummary->whereDate('date', $timestamp);
        }

        $attendancesSummary = $attendancesSummary->first();


        if (!$attendancesSummary) {
            $attendancesSummary = new AttendancesSummary([
                'date' => $timestamp->format('Y-m-d'),
                'employee_id' => $attendances->employee_id,
                'work_time_id' => $workTime?->workTime?->id ?? $workTime->id,
            ]);
        }

        if ($attendances->status1 === 0 && !$attendancesSummary->clock_in) {
            $newTimestamp = null;
            $expectedClockIn = Carbon::parse($timestamp->format('Y-m-d') . ' ' . $workTime->clock_in)->addDays();


            if ($timestamp->greaterThan($expectedClockIn) && $workTime?->workTime?->name === "Malam") {
                $newTimestamp = $timestamp->copy()->addDays();
            }
            $attendancesSummary->clock_in = $newTimestamp ?: $timestamp;
        } elseif ($attendances->status1 === 1 && !$attendancesSummary->clock_out) {
            $newTimestamp = null;
            $expectedClockOut = Carbon::parse($timestamp->format('Y-m-d') . ' ' . $workTime->clock_in);
            if ($timestamp->greaterThan($expectedClockOut) && $workTime?->workTime?->name === "Malam") {
                $newTimestamp = $timestamp->copy()->addDays();
            }
            $attendancesSummary->clock_out = $newTimestamp ?: $timestamp;
        }

        $attendancesSummary->save();
    }
}
