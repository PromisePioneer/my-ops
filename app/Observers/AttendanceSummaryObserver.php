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

        $user = User::where('absent_id', $attendances->employee_id)->first();

        $workTime =  WorkTime::whereHas('userWorktime', function ($item) use ($attendances, $user) {
            $item->where('user_id', $user->id);
            })->first();
        if ($attendances->status1 === 0) {
            $workTime = WorkTime::whereTime('time_to_checkin', '<=', $timestamp->toTimeString())
                ->whereTime('end_time_to_checkin', '>=', $timestamp->toTimeString())
                ->first();
        } elseif ($attendances->status1 === 1) {
            $workTime = WorkTime::whereTime('time_to_checkout', '<=', $timestamp->toTimeString())
                ->whereTime('end_time_to_checkout', '>=', $timestamp->toTimeString())
                ->first();
        }

        if (!$workTime) {
            Log::warning('No matching WorkTime found for timestamp: ' . $timestamp);
            return;
        }

        $attendancesSummary = AttendancesSummary::where('employee_id', $attendances->employee_id)
            ->where('work_time_id', $workTime->id)
            ->where(function ($query) use ($timestamp) {
                $query->where('date', $timestamp->format('Y-m-d'))
                    ->orWhere('date', $timestamp->copy()->subDay()->format('Y-m-d'));
            })
            ->first();


        if (!$attendancesSummary) {
            $attendancesSummary = new AttendancesSummary([
                'date' => $timestamp->format('Y-m-d'),
                'employee_id' => $attendances->employee_id,
                'work_time_id' => $workTime->id,
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
