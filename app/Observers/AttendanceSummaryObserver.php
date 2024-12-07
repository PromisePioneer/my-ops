<?php

namespace App\Observers;

use App\Models\Attendances;
use App\Models\AttendancesSummary;
use App\Models\EmployeeSchedule;
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
        $findLastCheckIn = AttendancesSummary::where('employee_id', $attendances->employee_id)
        ->whereNull('clock_out')
        ->latest()
        ->first();

    $clockIn = Carbon::parse($attendances->timestamp);

    // Adjust clockIn date based on work_time_id
    if ($findLastCheckIn && $findLastCheckIn->work_time_id === 6) {
        $clockIn = Carbon::parse($findLastCheckIn->date);
        Log::info('Adjusted clockIn based on last check-in: ' . $clockIn->toDateTimeString());
    }

        $userWorktime = EmployeeSchedule::with('workTime')
        ->where('employee_id', $attendances->employee_id)
        ->whereDate('date', Carbon::parse($findLastCheckIn->date))
        ->first() ?? WorkTime::where('name', 'Default')->first();



    // Update or create attendance summary
    $attendancesSummary = AttendancesSummary::updateOrCreate([
        'date' => $clockIn->format('Y-m-d'),
        'employee_id' => $attendances->employee_id,
        'work_time_id' => $userWorktime?->workTime?->id ?? $userWorktime->id,
    ], []);

    // Handle clock_in or clock_out updates
    if ($attendances->status1 === 0) {
        $attendancesSummary->clock_in = $clockIn->format('H:i');
        Log::info('Clock-in recorded: ' . $attendancesSummary->clock_in);
    } elseif ($attendances->status1 === 1) {
        $clockOutTime = Carbon::parse($attendances->timestamp)->format('H:i:s');
        if ($attendancesSummary->clock_in === null) {
            $attendancesSummary->date = $clockIn->subDay()->format('Y-m-d');
            $attendancesSummary->clock_out = Carbon::parse($clockIn->format('Y-m-d') . ' ' . $clockOutTime)->subDay()->format('H:i');
        } else {
            $attendancesSummary->clock_out = Carbon::parse($clockIn->format('Y-m-d') . ' ' . $clockOutTime)->format('H:i');
        }


        Log::info('Clock-out recorded: ' . $attendancesSummary->clock_out);
    }

    // Save the attendance summary
    $attendancesSummary->save();

    // Final debug log
    Log::info('Final AttendancesSummary: ', $attendancesSummary->toArray());

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
