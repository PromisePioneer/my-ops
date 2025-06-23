<?php

namespace App\Enum\LeaveAndPermission;

enum LeaveType: string
{
    case SICK = 'Sakit';
    case PERMISSION = 'Izin';
    case PAID_LEAVE = 'Cuti';
}
