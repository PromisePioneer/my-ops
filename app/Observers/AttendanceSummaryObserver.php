<?php

namespace App\Observers;

use App\Models\Attendances;
use App\Models\AttendancesSummary;
use App\Models\User;
use App\Models\WorkTime;
use Carbon\Carbon;
use Log;

class AttendanceSummaryObserver
{
    /**
     * Handle the Attendances "created" event.
     */
    public function created(Attendances $attendances): void
    {
        $user = User::where('absent_id', $attendances->employee_id)->first() ?? null;

        $userWorktime = WorkTime::whereHas('userWorktime', function ($item) use ($attendances, $user) {
            $item->where('user_id', $user->id);
        })->first() ?? WorkTime::where('name', 'Default')->first();

        $attendancesSummary = AttendancesSummary::updateOrCreate([
            'date' => Carbon::parse($attendances->timestamp)->format('Y-m-d'),
            'employee_id' => $attendances->employee_id,
            'work_time_id' => $userWorktime->id,
        ], []);

        if ($attendances->status1 === 0) {
            $attendancesSummary->clock_in = Carbon::parse($attendances->timestamp)->format('H:i');
        } elseif ($attendances->status1 === 1) {
            $attendancesSummary->clock_out = Carbon::parse($attendances->timestamp)->format('H:i');
        }

        $attendancesSummary->save();


        // if ($attendances->status1 === 0) {
        //      AttendancesSummary::create([
        //         'user_id' => $user->id,
        //         'date' => $attendances->timestamp,
        //         'clock_in' => Carbon::parse($attendances->timestamp)->format('H:i'),
        //     ]);
        // }

        // $date = Carbon::parse($attendances->timestamp)->toDateString();

        // if ($attendances->status1 === 1) {
        //     $update = AttendancesSummary::join('users', 'users.id', '=',
        //         'attendances_summary.user_id')
        //     ->join('attendances', 'attendances.employee_id', '=' ,'users.absent_id')
        //     ->where('attendances_summary.user_id', $user?->id)
        //     ->whereDate('attendances_summary.date', $date)
        //     ->select('attendances_summary.*')
        //     ->first();


        //     if($attendances->where('status1', 1)->where('employee_id', $user->absent_id)->where('timestamp', $update->date)->exists()){
        //         AttendancesSummary::create([
        //             'user_id' => $user->id,
        //             'date' => $attendances->timestamp,
        //             'clock_out' => Carbon::parse($attendances->timestamp)->format('H:i'),
        //         ]);
        //     }else{
        //         $update->clock_out =  Carbon::parse($attendances->timestamp)->format('H:i');
        //         $update->save();
        //     }


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
