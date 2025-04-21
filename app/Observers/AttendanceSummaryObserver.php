<?php

namespace App\Observers;

use App\Models\Attendances;
use App\Models\AttendancesSummary;
use App\Models\EmployeeSchedule;
use App\Models\User;
use App\Models\WorkTime;
use Carbon\Carbon;

class AttendanceSummaryObserver
{
    public function created(Attendances $attendances): void
    {
        $timestamp = Carbon::parse($attendances->timestamp);
        $user = User::where('absent_id', $attendances->employee_id)->first() ?? null;

        if (!$user) {
            return;
        }

        $workTime = $this->getWorkTime($attendances, $user, $timestamp);

        if (!$workTime) {
            return;
        }

        $attendancesSummary = $this->findOrCreateSummary($attendances, $workTime, $timestamp);

        if ($attendances->status1 === 0 && !$attendancesSummary->clock_in) {
            $attendancesSummary->clock_in = $timestamp;
        } elseif ($attendances->status1 === 1 && !$attendancesSummary->clock_out) {
            $attendancesSummary->clock_out = $timestamp;
        }

        $attendancesSummary->save();
    }

    private function getWorkTime(Attendances $attendances, User $user, Carbon $timestamp): ?WorkTime
    {
        $isEngineer = $user->hasAnyRole(['Engineer', 'Senior Engineer', 'KU Engineer', 'Quality Control Staff', 'Warehouse Security']) ? WorkTime::find(12) : null;

        $ifBranchDuri = $user->branch_id === 2 ? WorkTime::find(14) : null;

        $userShift = null;

        if ($timestamp->between(Carbon::parse($timestamp->copy()->format('Y-m-d') . '23:00:00'), Carbon::parse($timestamp->copy()->format('Y-m-d') . '23:59:59'))) {
            $userShift = EmployeeSchedule::with('workTime')
                ->where('employee_id', $attendances->employee_id)
                ->whereDate('start_date', $timestamp)
                ->first()?->workTime;
        }

        if ($timestamp->between(Carbon::parse($timestamp->copy()->format('Y-m-d') . '00:00:00'), Carbon::parse($timestamp->copy()->format('Y-m-d') . '02:00:00'))) {
            $userShift = EmployeeSchedule::with('workTime')
                ->where('employee_id', $attendances->employee_id)
                ->whereDate('end_date', $timestamp)
                ->first()?->workTime;
        }


        if ($attendances->status1 === 1 && $timestamp->between(Carbon::parse($timestamp->copy()->format('Y-m-d') . '09:00:00'), Carbon::parse($timestamp->copy()->format('Y-m-d') . '12:00:00'))) {
            $userShift = EmployeeSchedule::with('workTime')
                ->where('employee_id', $attendances->employee_id)
                ->whereDate('end_date', $timestamp->format('Y-m-d'))
                ->first()?->workTime;
        }

        if (!$userShift) {
            $userShift = EmployeeSchedule::with('workTime')
                ->where('employee_id', $attendances->employee_id)
                ->whereDate('start_date', $timestamp)
                ->first()?->workTime;
        }

        return $userShift ?? $ifBranchDuri ?? $isEngineer ?? WorkTime::find(11);
    }

    private function findOrCreateSummary(Attendances $attendances, WorkTime $workTime, Carbon $timestamp): AttendancesSummary
    {
        $queryDate = $this->getShiftDate($timestamp, $workTime, $attendances);

        $summary = AttendancesSummary::where('employee_id', $attendances->employee_id)
            ->where('work_time_id', $workTime->id)->whereDate('date', $queryDate->format('Y-m-d'))
            ->first();

        if (!$summary) {
            $summary = new AttendancesSummary([
                'date' => $queryDate->format('Y-m-d'),
                'employee_id' => $attendances->employee_id,
                'work_time_id' => $workTime->id,
            ]);
        }

        return $summary;
    }


    private function getShiftDate(Carbon $timestamp, WorkTime $workTime, $attendances): Carbon|null
    {
        $userShift = null;

        if ($timestamp->between(Carbon::parse($timestamp->copy()->format('Y-m-d') . '23:00:00'), Carbon::parse($timestamp->copy()->format('Y-m-d') . '23:59:59'))) {
            $userShift = EmployeeSchedule::with('workTime')
                ->where('employee_id', $attendances->employee_id)
                ->whereDate('start_date', $timestamp->format('Y-m-d'))
                ->first()?->start_date;
        }

        if ($timestamp->between(Carbon::parse($timestamp->copy()->format('Y-m-d') . '00:00:00'), Carbon::parse($timestamp->copy()->format('Y-m-d') . '02:00:00'))) {
            $userShift = EmployeeSchedule::with('workTime')
                ->where('employee_id', $attendances->employee_id)
                ->whereDate('end_date', $timestamp->format('Y-m-d'))
                ->first()?->start_date;
        }

        if ( $attendances->status1 === 1 && $timestamp->between(Carbon::parse($timestamp->copy()->format('Y-m-d') . '03:00:00'), Carbon::parse($timestamp->copy()->format('Y-m-d') . '06:00:00'))) {
            $userShift = EmployeeSchedule::with('workTime')
                ->where('employee_id', $attendances->employee_id)
                ->whereDate('end_date', $timestamp->format('Y-m-d'))
                ->first()?->start_date;
        }


        if ($attendances->status1 === 1 && $timestamp->between(Carbon::parse($timestamp->copy()->format('Y-m-d') . '09:00:00'), Carbon::parse($timestamp->copy()->format('Y-m-d') . '12:00:00'))) {
            $userShift = EmployeeSchedule::with('workTime')
                ->where('employee_id', $attendances->employee_id)
                ->whereDate('end_date', $timestamp->format('Y-m-d'))
                ->first()?->start_date;
        }

        if (!$userShift) {
            $userShift = EmployeeSchedule::with('workTime')
                ->where('employee_id', $attendances->employee_id)
                ->whereDate('start_date', $timestamp->format('Y-m-d'))
                ->first()?->start_date;
        }

        return Carbon::parse($userShift ?? $timestamp);
    }
}
