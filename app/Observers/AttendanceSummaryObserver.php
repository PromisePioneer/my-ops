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
    /**
     * Handle the Attendances "created" event.
     */
    public function created(Attendances $attendances): void
    {
        $user = User::where('absent_id', $attendances->employee_id)->first() ?? null;

        // $userWorktime = WorkTime::whereHas('userWorktime', function ($item) use ($attendances, $user) {
        //     $item->where('user_id', $user->id);
        // })->first() ?? WorkTime::where('name', 'Default')->first();


        $userWorktime =  EmployeeSchedule::with('workTime')
        ->where('employee_id', $attendances->employee_id)
        ->whereDate('date', Carbon::parse($attendances->timestamp))
        ->first() ?? WorkTime::where('name', 'Default')->first();


        Log::error($userWorktime);



        if ($userWorktime->status === 'L') {
            return;
        }


        $attendancesSummary = AttendancesSummary::updateOrCreate([
            'date' => Carbon::parse($attendances->timestamp)->format('Y-m-d'),
            'employee_id' => $attendances->employee_id,
            'work_time_id' => $userWorkTime?->workTime?->id ?? $userWorktime->id,
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
