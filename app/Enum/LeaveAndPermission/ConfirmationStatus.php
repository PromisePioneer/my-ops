<?php

namespace App\Enum\LeaveAndPermission;

enum ConfirmationStatus: string
{
    case ACCEPTED = 'Diterima';
    case REJECTED = 'Ditolak';
}
