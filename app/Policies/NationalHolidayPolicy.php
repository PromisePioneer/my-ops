<?php

namespace App\Policies;

use App\Models\NationalHoliday;
use App\Models\User;

class NationalHolidayPolicy
{
    public function view(User $user, NationalHoliday $nationalHoliday): bool
    {
        return $user->can('Lihat Libur Nasional');
    }

    public function create(User $user): bool
    {
        return $user->can('Tambah Libur Nasional');
    }
}
