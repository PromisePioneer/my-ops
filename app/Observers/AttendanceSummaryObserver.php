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
        $user = User::with('branch')
            ->join('attendances', 'users.absent_id', '=',
                'attendances.employee_id')
            ->where('attendance.employee_id', $attendances->employee_id)
            ->select('users.*', 'attendances.*')
            ->first();

        if ($attendances->status1 === 0) {
            $attendancesSummary = AttendancesSummary::create([
                'user_id' => $user->id,
                'date' => $attendances->timestamp,
                'clock_in' => Carbon::parse($attendances->timestamp)->format('H:i'),
            ]);

            if ($attendances->status1 === 1) {
                $attendancesSummary->clock_out = Carbon::parse($attendances->timestamps)->format('H:i');
            }
        }
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
