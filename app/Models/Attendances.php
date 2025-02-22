<?php

namespace App\Models;

use App\Observers\AttendanceSummaryObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[ObservedBy([AttendanceSummaryObserver::class])]
class Attendances extends Model
{
    use HasFactory;

    protected $table = 'attendances';

    protected $fillable = [
        'sn',
        'table',
        'stamp',
        'employee_id',
        'timestamp',
        'status1',
    ];


    public function trackableKey(): ?string
    {
        return (string)$this->id;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'employee_id', 'absent_id');
    }



}
