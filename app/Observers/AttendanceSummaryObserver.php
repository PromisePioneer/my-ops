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


        // Fetch user and role
        $user = User::where('absent_id', $attendances->employee_id)->first();
        if (!$user) {
            Log::warning("No user found for employee_id: {$attendances->employee_id}");
            return;
        }

        // Fetch WorkTime based on user role and shift timing
        $workTime = $this->getWorkTime($attendances, $user, $timestamp);


        if (!$workTime) {
            Log::warning('No matching WorkTime found for timestamp: ' . $timestamp);
            return;
        }


        // Find or create AttendancesSummary
        $attendancesSummary = $this->findOrCreateSummary($attendances, $workTime, $timestamp);

//        dd($attendancesSummary);

        // Update clock-in or clock-out
        if ($attendances->status1 === 0 && !$attendancesSummary->clock_in) {
            $attendancesSummary->clock_in = $this->adjustClockIn($attendances, $workTime, $timestamp);
        } elseif ($attendances->status1 === 1 && !$attendancesSummary->clock_out) {
            $attendancesSummary->clock_out = $timestamp;
        }

        $attendancesSummary->save();
    }

    private function getWorkTime(Attendances $attendances, User $user, Carbon $timestamp): ?WorkTime
    {

        $isEngineer = $user->hasRole('Engineer');
        $queryDate = $timestamp->copy();

        // Handle night shifts crossing midnight
        if ($timestamp->toTimeString() <= '02:00:00') {
            $queryDate->subDay();
        }

        $workTime = $isEngineer
            ? WorkTime::find(2)
            : EmployeeSchedule::with('workTime')
                ->where('employee_id', $attendances->employee_id)
                ->whereDate('date', $queryDate)
                ->first()?->workTime;


        return $workTime ?: WorkTime::find(1); // Default WorkTime
    }

    private function findOrCreateSummary(Attendances $attendances, WorkTime $workTime, Carbon $timestamp): AttendancesSummary
    {
        $queryDate = $this->getShiftDate($timestamp, $workTime);


        $summary = AttendancesSummary::where('employee_id', $attendances->employee_id)
            ->where('work_time_id', $workTime?->workTime?->id ?? $workTime->id);


        if ($attendances->status1 === 1 && $workTime->name === "Malam" || $workTime->name === "Sore") {
            $summary->whereDate('date', $queryDate)->orWhereDate('date', $queryDate->subDay());
        }

        if ($attendances->status1 === 0 || $attendances->status1 === 1) {
            $summary->whereDate('date', $queryDate);
        }


        $summary = $summary->first();


//        dd($summary);

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
        $expectedClockIn = Carbon::parse($timestamp->format('Y-m-d') . ' ' . $workTime->clock_in);
        return $timestamp;
    }


    private function getShiftDate(Carbon $timestamp, WorkTime $workTime): Carbon
    {
        // If shift is 'Malam' and time is between 00:00 and 02:00, it belongs to the previous day
        if ($workTime->name === 'Malam' && $timestamp->toTimeString() <= '02:00:00') {
            return $timestamp->copy()->subDay();
        }
        return $timestamp->copy();
    }

}
