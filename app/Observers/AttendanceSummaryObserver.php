<?php

namespace App\Observers;

use App\Models\Attendances;
use App\Models\AttendancesSummary;
use App\Models\User;
use Carbon\Carbon;

class AttendanceSummaryObserver
{
    /**
     * Handle the Attendances "created" event.
     */
    public function created(Attendances $attendances): void
    {
        $attendancesSummary = AttendancesSummary::updateOrCreate([
            'date' => Carbon::parse($attendances->timestamp)->format('Y-m-d'),
            'employee_id' => $attendances->employee_id,
        ], []);

        if ($attendances->status1 === 0) {
            $attendancesSummary->clock_in = Carbon::parse($attendances->timestamp)->format('H:i');
        } elseif ($attendances->status1 === 1) {
            $attendancesSummary->clock_out = Carbon::parse($attendances->timestamp)->format('H:i');
        }

        $attendancesSummary->save();
    }


    /**
     * Handle the Attendances "updated" event.
     */
    public function updated(Attendances $attendances): void
    {
        //
    }

    /**
     * Handle the Attendances "deleted" event.
     */
    public function deleted(Attendances $attendances): void
    {
        //
    }

    /**
     * Handle the Attendances "restored" event.
     */
    public function restored(Attendances $attendances): void
    {
        //
    }

    /**
     * Handle the Attendances "force deleted" event.
     */
    public function forceDeleted(Attendances $attendances): void
    {
        //
    }
}
