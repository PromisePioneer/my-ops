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


        // // Fetch user and role
        $user = User::where('absent_id', $attendances->employee_id)->first();
        if (!$user) {
            // Log::warning("No user found for employee_id: {$attendances->employee_id}");
            return;
        }

        // Fetch WorkTime based on user role and shift timing
        $workTime = $this->getWorkTime($attendances, $user, $timestamp);


        if (!$workTime) {
            // Log::warning('No matching WorkTime found for timestamp: ' . $timestamp);
            return;
        }


        // Find or create AttendancesSummary
        $attendancesSummary = $this->findOrCreateSummary($attendances, $workTime, $timestamp);

        // Update clock-in or clock-out
        if ($attendances->status1 === 0 && !$attendancesSummary->clock_in) {
            $attendancesSummary->clock_in = $timestamp;
        } elseif ($attendances->status1 === 1 && !$attendancesSummary->clock_out) {

            $attendancesSummary->clock_out = $timestamp;
        }

        $attendancesSummary->save();
    }

    private function getWorkTime(Attendances $attendances, User $user, Carbon $timestamp): ?WorkTime
    {

        $isEngineer = $user->hasRole('Engineer') ? WorkTime::find(2) : null;



        $checkSchedule = EmployeeSchedule::with('workTime')
        ->where('employee_id', $attendances->employee_id)
        ->whereDate('start_date', $timestamp)->first()?->workTime;


        return $checkSchedule ?? $isEngineer ?? WorkTime::find(1);
    }

    private function findOrCreateSummary(Attendances $attendances, WorkTime $workTime, Carbon $timestamp): AttendancesSummary
    {
        $queryDate = $this->getShiftDate($timestamp, $workTime, $attendances);


        $summary = AttendancesSummary::where('employee_id', $attendances->employee_id)
            ->where('work_time_id', $workTime->id)->whereDate('date', $queryDate);


        $summary = $summary->first();

        if (!$summary) {
            $summary = new AttendancesSummary([
                'date' => $queryDate->format('Y-m-d'),
                'employee_id' => $attendances->employee_id,
                'work_time_id' => $workTime->id,
            ]);
        }

        return $summary;
    }

    private function adjustClockIn(Attendances $attendances, WorkTime $workTime, Carbon $timestamp): Carbon
    {
        return $timestamp;
    }


    private function getShiftDate(Carbon $timestamp, WorkTime $workTime, $attendances): Carbon
    {
        $date = EmployeeSchedule::where('work_time_id', $workTime->id)
            ->where('employee_id', $attendances->employee_id)->whereDate('start_date', $timestamp)->orWhereDate('end_date', $timestamp);


        $dates = $date->first()?->start_date ?? $timestamp;

//        dd($date->first());

        return Carbon::parse($dates);
    }

}
