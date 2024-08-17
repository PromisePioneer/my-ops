<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManageShift extends Model
{
    use HasFactory;

    protected $table = 'manage_shift';
    protected $fillable = [
        'name',
        'clock_in',
        'clock_out',
        'time_to_checkin',
        'end_time_to_checkin',
        'time_to_checkout',
        'end_time_to_checkout'
    ];


    public function getDataWithPagination(int|null $branchId, int $perPage)
    {
        return self::where('branch_id', $branchId)->paginate($perPage);
    }

}
