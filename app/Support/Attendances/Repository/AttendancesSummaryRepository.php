<?php

namespace App\Support\Attendances\Repository;

use App\Models\User;

class AttendancesSummaryRepository
{
    public function getAttendancesSummary($startDate, $endDate)
    {
        return User::select('id', 'nip', 'name', 'profile_pic', 'active', 'absent_id')
            ->with([
                'attendancesSummary' => function ($query) use ($startDate, $endDate) {
                    $query->select('date', 'employee_id', 'date', 'clock_in', 'clock_out', 'work_time_id')
                        ->whereBetween('date', [$startDate, $endDate])->distinct('date')
                        ->with('workTime:id,clock_in,name');
                },
                'employeeSchedules' => function ($query) use ($startDate, $endDate) {
                    $query->select('employee_id', 'start_date', 'status')
                        ->where('start_date', '<=', $startDate)
                        ->orderBy('start_date', 'asc');
                },
                'leaveAndPermissions' => function ($query) use ($startDate, $endDate) {
                    $query->select('user_id', 'start_date', 'end_date', 'confirmation_status', 'leaves_status')
                        ->where('confirmation_status', 'Diterima')
                        ->where(function ($q) use ($startDate, $endDate) {
                            $q->whereBetween('start_date', [$startDate, $endDate])
                                ->orWhereBetween('end_date', [$startDate, $endDate]);
                        });
                },
                'roles:id,name',
                'weekHoliday:user_id,day',
                'company:id,name',
            ])
            ->where('active', 1)
            ->orderBy('name');
    }
}
