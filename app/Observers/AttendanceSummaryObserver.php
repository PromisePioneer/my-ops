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
                ->first();


        } elseif ($attendances->status1 === 1) {
            $workTime = $user->hasRole('Engineer') ? WorkTime::find(2) : WorkTime::whereTime('time_to_checkout', '<=', $timestamp->toTimeString())
            ->whereTime('end_time_to_checkout', '>=', $timestamp->toTimeString())
            ->first() ?? EmployeeSchedule::with('workTime')
            ->where('employee_id', $attendances->employee_id)
            ->whereDate('date', $timestamp)
            ->first();
        }


        if ($workTime?->status === 'L') {
            return;
        }

        if (!$workTime) {
            Log::warning('No matching WorkTime found for timestamp: ' . $timestamp);
            return;
        }

        $attendancesSummary = AttendancesSummary::where('employee_id', $attendances->employee_id)
            ->where('work_time_id', $workTime?->workTime?->id ?? $workTime->id)
            ->where(function ($query) use ($timestamp) {
                $query->whereDate('date', $timestamp)
                    ->orWhereDate('date', $timestamp->copy()->subDay());
            })
            ->latest()->first();


            Log::info($attendancesSummary);



        if (!$attendancesSummary) {
            $attendancesSummary = new AttendancesSummary([
                'date' => $timestamp->format('Y-m-d'),
                'employee_id' => $attendances->employee_id,
                'work_time_id' => $workTime?->workTime?->id ?? $workTime->id,
            ]);
        }

        if ($attendances->status1 === 0 && !$attendancesSummary->clock_in) {
            $attendancesSummary->clock_in = $timestamp;
        } elseif ($attendances->status1 === 1 && !$attendancesSummary->clock_out) {
            $attendancesSummary->clock_out = $timestamp;
        }

        $attendancesSummary->save();
    }
}
